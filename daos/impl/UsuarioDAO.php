<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'DAOBasico.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
require_once 'ftbp-src/daos/impl/GrupoDAO.php';
require_once 'ftbp-src/daos/impl/lazy/UsuarioLazy.php';
require_once 'ftbp-src/entidades/basico/Usuario.php';
/*
 * UsuarioDAO.php
 */

/**
 * Description of UsuarioDAO
 *
 * @author Luis
 * @since Feb 23, 2013
 */
class UsuarioDAO extends DAOBasico {

    /**
     * @param Usuario $entidade
     * @throws Exception
     */
    public function executarInsert(Entidade $entidade) {

        $sql = "INSERT 
                 INTO usuarios(
                      nome,
                      email,
                      senha,
                      departamento_id,
                      responsavel,
                      tipo_usuario)
               VALUES ($1, $2, $3, $4, " . ( $entidade->getResponsavel() ? 'true' : 'false') . ", $5)";


        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getEmail(), PreparedStatement::STRING);
        $p->setParameter(3, hash("sha512", $entidade->getSenha()), PreparedStatement::STRING);

        if ($entidade->getDepartamento() == NULL) {
            $p->setParameter(4, NULL, PreparedStatement::INTEGER);
        } else {
            $p->setParameter(4, $entidade->getDepartamento()->getId(), PreparedStatement::INTEGER);
        }

        $p->setParameter(5, $entidade->getTipoUsuario(), PreparedStatement::INTEGER);

        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('usuarios_id_seq') as id");
        $p->next();
        $array = $p->fetchArray();

        $entidade->setId($array['id']);
    }

    public function executarUpdate(Entidade $entidade) {

        $sql = "UPDATE usuarios
                   SET nome            = $1,
                       email           = $2,
                       departamento_id = $3,
                       responsavel     = " . ( $entidade->getResponsavel() ? 'true' : 'false') . ",
                       tipo_usuario    = $4,
                       grupo_id        = $5";
        if ($entidade->getSenha() != '') {
            $sql .= ",senha           = $7 ";
        }
        $sql .= "WHERE id = $6";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getEmail(), PreparedStatement::STRING);

        if ($entidade->getDepartamento() == NULL) {
            $p->setParameter(3, NULL, PreparedStatement::INTEGER);
        } else {
            $p->setParameter(3, $entidade->getDepartamento()->getId(), PreparedStatement::INTEGER);
        }
        $p->setParameter(4, $entidade->getTipoUsuario(), PreparedStatement::INTEGER);

        $p->setParameter(5, $entidade->getGrupo()->getId(), PreparedStatement::INTEGER);
        
        $p->setParameter(6, $entidade->getId(), PreparedStatement::INTEGER);

        if ($entidade->getSenha() != '') {
            $p->setParameter(7, hash("sha512", $entidade->getSenha()), PreparedStatement::STRING);
        }

        $p->execute();
    }

    public function executarDelete(Entidade $entidade) {
        $sql = " delete from      
                      usuarios where id=$1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);
        $p->execute();
    }

    /**
     * 
     * @param string $email
     * @param string $senha
     * @return Aluno
     * @throws NoResultException
     */
    public function login($email, $senha) {

        $sql = "select *  
                 from usuarios
                where senha = $1 and email = $2";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, hash("sha512", $senha), PreparedStatement::STRING);
        $p->setParameter(2, $email, PreparedStatement::STRING);

        $rs = $p->getResult();
        if (!$rs->next()) {
            throw new NoResultException("Usuário não encontrado");
        }

        $usuario = $this->montarUsuario($rs);

        /*
         * Força o carregamento do setor e do grupo.
         * É necessário pois a sessão não mantém a instancia correta do dao em
         * memória, logo para que o usuário fique completo 
         * (quando chamamos o metodo montarUsuario uma instancia não completa do
         * usuário é retornando, ela é carregada sobdemanda).
         */
        
        $usuario->getDepartamento();
        $usuario->getGrupo();
        
        // Carrega o acesso do usuário
        $grupoDAO = new GrupoDAO();
        $grupoDAO->setConn($this->getConn());
        $grupoDAO->carregarAcesso($usuario->getGrupo());
        
        return $usuario;
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Usuario
     */
    public function montarUsuario(ResultSet $rs) {
        $arr = $rs->fetchArray();

        $u = new UsuarioLazy($this);
        $u->setEmail($arr['email']);
        $u->setNome($arr['nome']);
        $u->setId($arr['id']);
        $u->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        $u->setResponsavel($arr['responsavel'] === 't');
        $u->setTipoUsuario($arr['tipo_usuario']);
        $u->setDepartamentoId($arr['departamento_id']);
        $u->setGrupoId($arr['grupo_id']);

        return $u;
    }

    /**
     * @param integer $id
     * @return Aluno
     * @throws NoResultException
     */
    public function getById($id) {
        $sql = "select *  
                 from usuarios
                where id = $1";

        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $id, PreparedStatement::INTEGER);
        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException("Usuário não encontrado");
        }

        return $this->montarUsuario($rs);
    }

    /**
     * Carrega o usuário com o departamento relacionado.
     * @param Usuario $usuario
     * @throws NoResultException quanto não encontra o Departamento.
     */
    public function carregarDepartamentoUsuario(Usuario $usuario, $dpartamentId) {
        $sql = "select * 
                  from departamento 
                 where id = $1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $dpartamentId, PreparedStatement::INTEGER);

        $rs = $p->getResult();

        if (!$rs->next()) {
            throw new NoResultException(
            "Departamento com o id " . $dpartamentId . " não encontrando");
        }

        $arr = $rs->fetchArray();
        $dp = new Departamento();
        $dp->setId($arr['id']);
        $dp->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        $dp->setNome($arr['nome']);

        $usuario->setDepartamento($dp);
    }

    /**
     * @param array $ids
     * @return array
     * @throws NoResultException
     */
    public function getByIds($ids) {

        $list = array();
        if ($ids != '' && is_array($ids) && !empty($ids)) {
            $sql = "select *  
                     from usuarios
                    where id in (" . DAOUtil::listToString($ids) . ")";

            $rs = $this->getConn()->query($sql);

            while ($rs->next()) {
                $list[] = $this->montarUsuario($rs);
            }
        }

        return $list;
    }

    /**
     * Carrega os responsáveis pelos departamentos solicitados, 
     * selecionados pelos @param $ids.
     * @param array $ids
     * @return List<Aluno>
     * @throws NoResultException
     */
    public function carregarResponsavelDepartamento($ids = array()) {

        $list = array();

        if (isset($ids) && is_array($ids) && count($ids) > 0) {

            $sql = "select *  
                 from usuarios
                where responsavel = true and departamento_id in (" . DAOUtil::listToString($ids) . ")";

            $rs = $this->getConn()->query($sql);

            while ($rs->next()) {
                $list[] = $this->montarUsuario($rs);
            }
        }

        return $list;
    }

    public function carregarTodosOsUsuarios() {

        $list = array();


        $sql = "select *  
                 from usuarios";

        $rs = $this->getConn()->query($sql);

        while ($rs->next()) {
            $list[] = $this->montarUsuario($rs);
        }
        return $list;
    }

    public function carregarUsuariosDepartamento($dep) {

        $list = array();

        if (isset($dep) && is_array($dep) && count($dep) > 0) {

            $sql = "select *  
                 from usuarios
                 where departamento_id in (" . DAOUtil::listToString($dep) . ")";

            $rs = $this->getConn()->query($sql);

            while ($rs->next()) {
                $list[] = $this->montarUsuario($rs);
            }
        }
        return $list;
    }

    public function carregarGrupo(Usuario $usuario, $grupoId) {

        if ($grupoId != '') {
            // Monta a query
            $sql = "select id, nome, data_criacao
                  from grupos
                 where id = $1";
            $p = $this->getConn()->prepare($sql);

            // Seta os parâmetros
            $p->setParameter(1, $grupoId, PreparedStatement::INTEGER);

            $rs = $p->getResult();

            if (!$rs->next()) {
                throw new NoResultException("Grupo não encontrado com id: " . $grupoId);
            }

            // Monta o grupo
            $g = new Grupo();

            $g->setId((int) $grupoId);
            $g->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
            $g->setNome($arr['nome']);

            $usuario->setGrupo($g);
            
        }
    }

}

?>
