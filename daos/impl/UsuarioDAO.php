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
class UsuarioDAO extends DAOBasico{

    public function executeDelete(Entidade $entidade) {
        
    }
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
        
    }

    public function executarDelete(Entidade $entidade) {
        
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
        if(!$rs->next()){
            throw new NoResultException("Usuário não encontrado");
        }
        
        return $this->montarAluno($rs);
        
    }
    
    /**
     * 
     * @param ResultSet $rs
     * @return Aluno
     */
    private function montarAluno(ResultSet $rs){
        $arr = $rs->fetchArray();
        $u = new Aluno();
        $u->setEmail($arr['email']);
        $u->setNome($arr['nome']);
        $u->setId($arr['id']);
        return $u;
    }

}

?>
