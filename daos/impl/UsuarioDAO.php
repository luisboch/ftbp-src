<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'DAOBasico.php';
require_once 'ftbp-src/daos/impl/DAOUtil.php';
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
               VALUES ($1, $2, $3, $4, ".( $entidade->getResponsavel()?'true':'false').", $5)";


        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getEmail(), PreparedStatement::STRING);
        $p->setParameter(3, hash("sha512", $entidade->getSenha()), PreparedStatement::STRING);
        
        if($entidade->getDepartamento() ==  NULL){
            $p->setParameter(4, NULL, PreparedStatement::INTEGER);
        } else{
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
                       senha           = $3,
                       departamento_id = $4,
                       responsável     = ".( $entidade->getResponsavel()?'true':'false').",
                       tipo_usuario    = $5
                 WHERE id = $6";
        
        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $entidade->getEmail(), PreparedStatement::STRING);
        $p->setParameter(3, hash("sha512", $entidade->getSenha()), PreparedStatement::STRING);
        
        if($entidade->getDepartamento() ==  NULL){
            $p->setParameter(4, NULL, PreparedStatement::INTEGER);
        } else{
            $p->setParameter(4, $entidade->getDepartamento()->getId(), PreparedStatement::INTEGER);
        }
        
        $p->setParameter(5, $entidade->getTipoUsuario(), PreparedStatement::INTEGER);
        
        $p->setParameter(6, $entidade->getId(), PreparedStatement::INTEGER);

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

        return $this->montarUsuario($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Usuario
     */
    private function montarUsuario(ResultSet $rs) {
        $arr = $rs->fetchArray();
        
        $u = new UsuarioLazy($this);
        $u->setEmail($arr['email']);
        $u->setNome($arr['nome']);
        $u->setId($arr['id']);
        $u->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        $u->setResponsavel($arr['responsavel']);
        $u->setTipoUsuario($arr['tipo_usuario']);
        $u->setDepartamentoId($arr['departamento_id']);
        
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
    public function carregarDepartamentoUsuario(Usuario $usuario, $dpartamentId){
        $sql = "select * 
                  from departamento 
                 where id = $1";
        $p = $this->getConn()->prepare($sql);
        $p->setParameter(1, $usuario->getDepartamento()->getId(), PreparedStatement::INTEGER);
        
        $rs = $p->getResult();
        
        if(!$rs->next()){
            throw new NoResultException(
                    "Departamento com o id ".$usuario->getDepartamento()->getId(). " não encontrando");
        }
        
        $arr = $rs->fetchArray();
        $dp = new Departamento();
        $dp->setId($arr['id']);
        $dp->setDataCriacao(DAOUtil::toDateTime($arr['data_criacao']));
        $dp->setNome($arr['nome']);
        
        $usuario->setDepartamento($dp);
        
    }
}

?>
