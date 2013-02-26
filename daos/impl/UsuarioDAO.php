<?php

require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'DAOBasico.php';
require_once 'ftbp-src/entidades/basico/Aluno.php';
require_once 'ftbp-src/entidades/basico/Funcionario.php';
require_once 'ftbp-src/entidades/basico/Professor.php';
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
        if ($entidade->getTipo() == 'Aluno') {
            $this->inserirAluno($entidade);
        } else {
            throw new
            Exception("Não foram implementadas outras formas para gravar usuário");
        }
    }

    public function executarUpdate(Entidade $entidade) {
        if ($entidade->getTipo() == 'Aluno') {
            $this->atualizarAluno($entidade);
        } else {
            throw new
            Exception("Não foram implementadas outras formas para gravar usuário");
        }
    }

    public function executarDelete(Entidade $entidade) {

        $sql = " delete from      
                      usuarios where id=$1";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $entidade->getId(), PreparedStatement::INTEGER);

        $p->execute();
    }

    private function inserirAluno(Aluno $aluno) {

        $sql = "INSERT 
                 INTO usuarios(
                      nome,
                      email,
                      senha,
                      data_criacao,
                      grupo_id)
               VALUES ($1, $2, $3, now(), NULL)";


        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $aluno->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $aluno->getEmail(), PreparedStatement::STRING);
        $p->setParameter(3, hash("sha512", $aluno->getSenha()), PreparedStatement::STRING);

        $p->execute();

        // Pega o id gerado na sequence 
        $p = $this->getConn()->query("select currval('usuarios_id_seq') as id");
        $p->next();
        $array = $p->fetchArray();

        $aluno->setId($array['id']);
    }

    private function atualizarAluno(Aluno $aluno) {

        $sql = "UPDATE usuarios(
                   SET nome     = $1,
                       email    = $2,
                       senha    = $3,
                       grupo_id = NULL)
                 WHERE id = $4";

        $p = $this->getConn()->prepare($sql);

        $p->setParameter(1, $aluno->getNome(), PreparedStatement::STRING);
        $p->setParameter(2, $aluno->getEmail(), PreparedStatement::STRING);
        $p->setParameter(3, hash("sha512", $aluno->getSenha()), PreparedStatement::STRING);
        $p->setParameter(4, $aluno->getId(), PreparedStatement::INTEGER);

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

        return $this->montarAluno($rs);
    }

    /**
     * 
     * @param ResultSet $rs
     * @return Aluno
     */
    private function montarAluno(ResultSet $rs) {
        $arr = $rs->fetchArray();
        $u = new Aluno();
        $u->setEmail($arr['email']);
        $u->setNome($arr['nome']);
        $u->setId($arr['id']);
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

        return $this->montarAluno($rs);
    }

}

?>
