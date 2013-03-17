<?php

require_once 'ftbp-src/entidades/Pesquisavel.php';
require_once 'ftbp-src/entidades/basico/Pesquisa.php';
require_once 'ftbp-src/daos/EntidadeDAO.php';
require_once 'ftbp-src/daos/impl/DAOBasico.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PesquisaDAO
 *
 * @author luis
 */
class PesquisaDAO extends DAOBasico {

    /**
     * @param Pesquisavel $entidade
     */
    public function executarDelete(Entidade $entidade) {

        // Pega o id da pesquisa
        $sql1 = "select id 
                  from pesquisa 
                 where entidade_id = $1 ";

        $p1 = $this->getConn()->prepare($sql1);

        $p1->setParameter(1, $entidade->getEntidade()->getId(), PreparedStatement::INTEGER);

        $rs = $p1->getResult();

        // se a entidade não existe ignora.
        if ($rs->next()) {

            $arr = $rs->fetchArray();

            $pesquisaId = $arr['id'];

            $sql2 = "delete 
                  from palavras_chave 
                 where pesquisa_id = $1";

            $p2 = $this->getConn()->prepare($sql2);

            $p2->setParameter(1, $pesquisaId, PreparedStatement::INTEGER);


            // deleta as palavras
            $p2->execute();

            // deleta a entidade.
            $sql3 = "delete
                  from pesquisa 
                 where entidade_id = $1 ";

            $p3 = $this->getConn()->prepare($sql3);

            $p3->setParameter(1, $entidade->getEntidade()->getId(), PreparedStatement::INTEGER);

            $p3->execute();
        }
    }

    /**
     * @param Pesquisavel $entidade
     */
    public function executarInsert(Entidade $entidade) {

        // pega o id da pesquisa
        $sql1 = "select nextval('pesquisa_id_seq') as id";

        $p1 = $this->getConn()->prepare($sql1);
        $rs1 = $p1->execute();
        $rs1->next();
        $arr = $rs1->fetchArray();
        $pesquisaId = $arr['id'];

        // Prepara a querie de inserção
        $sql2 = "insert 
                  into pesquisa(
                       id,
                       tipo,
                       entidade_id,
                       titulo, 
                       descricao, 
                       link)
                values ($1, $2, $3, $4, $5, $6) ";

        $p2 = $this->getConn()->prepare($sql2);

        // Seta os parãmetros
        $p2->setParameter(1, $pesquisaId, PreparedStatement::INTEGER);
        $p2->setParameter(2, $entidade->getTipo(), PreparedStatement::STRING);
        $p2->setParameter(3, $entidade->getId(), PreparedStatement::INTEGER);
        $p2->setParameter(4, $entidade->getTitulo(), PreparedStatement::STRING);
        $p2->setParameter(5, $entidade->getBreveDescricao(), PreparedStatement::STRING);
        $p2->setParameter(6, $entidade->getLink(), PreparedStatement::STRING);

        // Executa o insert
        $p2->execute();

        // Insere as palavras chave
        // Prepara a querie
        $sql3 = "insert
                   into palavras_chave(
                        pesquisa_id, 
                        palavra)
                 values ($1, $2)";

        $p3 = $this->getConn()->prepare($sql3);

        // seta os parametros e executa p insert para os dados.
        $palavras = $entidade->getPalavrasChave();
        if (is_array($palavras)) {
            foreach ($palavras as $k => $palavra) {
                $p3->setParameter(1, $pesquisaId, PreparedStatement::INTEGER);
                $p3->setParameter(2, $palavra, PreparedStatement::INTEGER);
                $p3->execute();
            }
        }
    }

    /**
     * @param Pesquisavel $entidade
     */
    public function executarUpdate(Entidade $entidade) {
        $this->executarDelete($entidade);
        $this->executarInsert($entidade);
    }

    /**
     * 
     * This method do not have implementation
     * @param integer $id
     * @throws IllegalStateException always
     * 
     */
    public function getById($id) {
        // não é possível pesquisar por id
        throw new IllegalStateException("Not implemented yet");
    }

    /**
     * 
     * @param List<Pesquisa> $string
     * @return Pesquisa
     */
    public function search($string = "") {

        // Prepara a querie levando em consideração apenas a tabela principal
        $sql = "select distinct p.* 
                  from pesquisa p ";
        $palavras = explode(' ', $string);

        // Adicioa joins para a tabela de palavras, registrando quais 
        // os parãmetros que devem ser setados

        $params = array();
        $i = 0;
        foreach ($palavras as $p) {
            $sql .= "join palavras_chave on (pesquisa_id = p.id and lower(palavra) like lower($" . ($i) . ") ";
            $i++;
            $params[] = $p;
        }
        $pr = $this->getConn()->prepare($sql);

        // Seta todos os parãmetros.
        for ($y = 0; $y < count($params); $y++) {
            $pr->setParameter($y, $params[$y], PreparedStatement::STRING);
        }

        $rs = $pr->getResult();

        // Monta a lista de resultados
        $list = array();
        while ($rs->next()) {
            $arr = $rs->fetchArray();

            $entidade =
                    new Pesquisa($arr['descricao'], $arr['entidade_id'], $arr['link'], $arr['titulo'], $arr['tipo']);
            $list[] = $entidade;
        }

        // Retorna
        return $list;
    }

}

?>