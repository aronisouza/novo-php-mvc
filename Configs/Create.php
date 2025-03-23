<?php

class Create extends Conexao {
	private $Tabela;
	private $Dados;
	private $Result;
	private $Places;
	
	//PDOStatement
	private $Create;
	
	//@var PDO
	private $Conn;
	
	/**
	 * ExeCreate: Executa um cadastro simplificado no banco de dados utilizando prepared statements.
	 * Basta informar o nome da tabela e um array atribuitivo com nome da coluna e valor!
	 *
	 * STRING $Tabela = Informe o nome da tabela no banco!
	 * ARRAY $Dados = Informe um array atribuitivo. ( Nome Da Coluna => Valor ).
	 */
	public function ExeCreate($Tabela, array $Dados) {
		$this->Tabela = (string) $Tabela;
		$this->Dados = $Dados;
		$this->getSyntax();
		$this->Execute();
	}
	/**
	 * Obter resultado: Retorna o ID do registro inserido ou FALSE caso nem um registro seja inserido!
	 * retorna INT $Variavel = lastInsertId OR FALSE
	 */
	public function getResult() {
		return $this->Result;
	}
	
	//Obtém o PDO e Prepara a query
	private function Connect() {
		$this->Conn = parent::getConn();
		$this->Create = $this->Conn->prepare($this->Create);
	}
	
	//Cria a sintaxe da query para Prepared Statements
	private function getSyntax() {
		$Fileds = implode(', ', array_keys($this->Dados));
		$Places = ':' . implode(', :', array_keys($this->Dados));
		$this->Create = "INSERT INTO {$this->Tabela} ({$Fileds}) VALUES ({$Places})";
	}

	public function FullCreate($Query, $ParseString = null)
	{
		$this->Create = (string) $Query;
    $this->Dados = [];
    if (!empty($ParseString)) :
      parse_str($ParseString, $this->Places);
    endif;
    $this->Execute();
	}
	
	//Obtém a Conexão e a Syntax, executa a query!
	private function Execute() {
		$this->Connect();
		try {

			$dados = is_array($this->Dados) ? $this->Dados : [];
			$places = is_array($this->Places) ? $this->Places : [];
			$this->Create->execute(array_merge($dados, $places));

			//$this->Create->execute($this->Dados);
			$this->Result = $this->Conn->lastInsertId();
		} catch (\PDOException $e) {
			$this->Result = null;
			logError("Erro PDO: {$e->getMessage()}");
			fldMessage('danger',"<b>Erro ao Criar:</b><br /> Mensagem: {$e->getMessage()}");
		}
	}
}