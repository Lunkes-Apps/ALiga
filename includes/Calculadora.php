<?php

class Calculadora{
	
    private $dbOp;
		
		function __construct(){
			
			require_once dirname(__FILE__).'/DbOperationsPDO.php';
			$this->dbOp = new DbOperationsPDO();
		}
	
    function atualizarPontos($idJogo){
		$apostas = $this->dbOp->apostasByJogo($idJogo);
		$jogo = $this->dbOp->getJogo($idJogo);
				
		$rodada = $jogo['rodada'];
		$idRodada = $jogo['id'];
		$pA = $jogo['placarA'];
		$pB= $jogo['placarB'];
		$aA;
		$aB;
		$p = 0;
		$c = count($apostas);
		
		for($i = 0; $i < $c; $i++){
			$aA = $apostas[$i]['placarA'];
			$aB = $apostas[$i]['placarB'];
			if($aA == $pA && $aB == $pB){// checar se acertou o placar foi igual
				$p = 3;				 
			    echo "if 1 </br>";
			}else{
				if($pA<$pB && $aA<$aB){//checar se acertou que B ganhou de A 
					$p = 1;
					echo "if 2 </br>";
				}else{
					if($pA>$pB && $aA>$aB){//checar se acertou que A ganhou de B
						$p = 1;
						echo "if 3 </br>";
				}else{
					if(($pA == $pB) && ($aA == $aB)){//checar se acertou que A empatou de B
						$p = 1;
						echo "if 4 </br>";
					}else{
						$p=0;
					}
				}
			}
			}
			echo "aposta A ".$aA." placar A ".$pA."</br>";
			echo "aposta B ".$aB." placar B ".$pB."</br>";
			
			echo "pontos ".$p." sendo salvo no user ".$apostas[$i]['idUser']."</br>";		
			$this->dbOp->salvarPontuacao($idJogo,$apostas[$i]['idUser'],$p);
		}		
		
	}

	
	
}

?>