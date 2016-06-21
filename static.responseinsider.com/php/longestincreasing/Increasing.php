<?php
class Increasing{
	protected $grid = array();
	private $sequence = array();
	private $currSeq = array();
	/*
	*  constructor accepts file and turns grid into array
	*  Requirements: file name has to be grid.txt, contain only numbers, and no extra spaces towards the end of lines
	*/
	public function __construct(){
		$fileCont = fopen('grid.txt', "r") or die("unable to open file");
		while(!feof($fileCont)){
			$line = fgets($fileCont);
			if(!empty($line)){
				$this->grid[] = explode(" ", $line);
			}
		}
	}
	
	public function getLongestPath(){
		$lis = 0;
		$currLis = 0;
		foreach($this->grid as $key => $val){
			foreach($val as $k => $v){
				$currLis = $this->recLisPath($key, $k);
				if($lis < $currLis){
					$lis = $currLis;
				}
			}
		}
		echo "Longest Increasing Sequence in the given grid is: " . $lis;
	}
	
	private function recLisPath($row, $col){
		$lis = 1;
		$tempLis = 0;
		//error_log("--------");
		if($row-1 >= 0 && $row-1 < count($this->grid) && $col   >= 0 && $col   < count($this->grid[$row]) 
					&& $this->grid[$row][$col] < $this->grid[$row-1][$col]){
						$tempLis = $this->recLisPath($row-1, $col);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row-1 >= 0 && $row-1 < count($this->grid) && $col+1 >= 0 && $col+1 < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row-1][$col+1]){
                        $tempLis = $this->recLisPath( $row-1, $col+1);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row   >= 0 && $row   < count($this->grid) && $col+1 >= 0 && $col+1 < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row][$col+1]){
                        $tempLis = $this->recLisPath( $row,   $col+1);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row+1 >= 0 && $row+1 < count($this->grid) && $col+1 >= 0 && $col+1 < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row+1][$col+1]){
                        $tempLis = $this->recLisPath( $row+1, $col+1);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row+1 >= 0 && $row+1 < count($this->grid) && $col   >= 0 && $col   < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row+1][$col]){
                        $tempLis = $this->recLisPath( $row+1, $col);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row+1 >= 0 && $row+1 < count($this->grid) && $col-1 >= 0 && $col-1 < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row+1][$col-1]){
                        $tempLis = $this->recLisPath( $row+1, $col-1);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row   >= 0 && $row   < count($this->grid) && $col-1 >= 0 && $col-1 < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row][$col-1]){
                        $tempLis = $this->recLisPath( $row,   $col-1);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
                if($row-1 >= 0 && $row-1 < count($this->grid) && $col-1 >= 0 && $col-1 < count($this->grid[$row]) 
							&& $this->grid[$row][$col] < $this->grid[$row-1][$col-1]){
                        $tempLis = $this->recLisPath( $row-1, $col-1);
                        if($tempLis >= $lis){
                                $lis = $tempLis + 1;
                        }
                }
		return $lis;

	}

}

$obj = new Increasing();
$obj->getLongestPath();

?>


