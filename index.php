<?php
$listdata="";$jmldata = 0; $minsupport="";$minconfidence="";
if(!empty($_POST)){
	$jmldata = $_POST['jmldata'];
	$listdata = $_POST['listdata'];
	$minsupport = $_POST['minsupport'];
	$minconfidence = $_POST['minconfidence'];
	$itemList = getItemList($listdata);
	$totalItemLists = getTotalEachItem($listdata);

}
//var_dump($hasil);
?>
<html>
<head>
<title>Data Mining</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<script src="highcharts.js"></script>
<script src="jquery/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Data Mining</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Apriori</a></li>
    </div>
  </div>
</nav>

<form class="" action="index.php" method="POST" autocomplete="off" >
	<div class="container">
		<div class="row">
			<div class="col-md-12">
					<div class="row">
					  <div class="col-md-6">
							<div class="form-group p-3">
								<label for="data">Masukkan Jumlah Data:</label>
								<input type="number" min='1' class="form-control  input-sm" name="jmldata" value="<?php echo $jmldata;?>" placeholder="Masukkan Jumlah Data" required>
							</div>
							<button type="submit" name="hitung" class="btn btn-success">Submit</button>
					  </div>
					</div>
			</div>	
			<?php 
			if($jmldata > 0){
				$listSupportItem = [];
			?>
			<div class="col-md-12">
				<br>	
				<span style="color:red">Nb: *Penulisan huruf besar dan kecil harus sama tiap item jika tidak,  maka dianggap item yang berbeda</span>
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr class="bg-primary">
							<td width="25">No</td>
							<td>Masukkan Data</td>
						</tr>
						<?php 
						for($i=0;$i<$jmldata;$i++){ 
						$item = "";
						if(isset($listdata[$i])){
							$item = $listdata[$i];
						}
						?>
						
						<tr>
							<td><?php echo $i+1;?></td>
							<td>
								<input type="text" class="form-control  input-sm" name="listdata[]" value="<?php echo $item;?>" placeholder="Contoh : Batik, Basic, Jangkis, Taqwa, Daster">
							</td>
						<tr>
						<?php }?>
					</table>
					<div class="form-group p-3">
						<label for="data">Masukkan Minimum support(%):</label>
						<input type="number" min='1' class="form-control  input-sm" name="minsupport" value="<?php echo $minsupport;?>" placeholder="Masukkan Minimum support(%)">
					</div>
					<div class="form-group p-3">
						<label for="data">Masukkan Minimum Confidence(%):</label>
						<input type="number" min='1' class="form-control  input-sm" name="minconfidence" value="<?php echo $minconfidence;?>" placeholder="Masukkan Minimum Confidence(%):">
					</div>
					<button type="submit" name="hitung" class="btn btn-success">Submit</button>
				</div>
				<br>	
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr class="bg-primary">
							<td colspan="4">Tabel 1</td>
						</tr>
						<tr class="bg-primary">
							<td width="25">No</td>
							<td>Item Pembelian</td>
							<td>Support Total</td>
							<td>Support Persen</td>
						</tr>
						<?php 
						$no=0;
						foreach($itemList as $key => $item){
						$no++;
						$percent = $totalItemLists[$key]/$jmldata*100;
						$class = "";
						if($percent >= $minsupport){
							$class ="bg-success";
							$listSupportItem[] = $item;
						}
						?>
						<tr class="<?php echo $class;?>">
							<td><?php echo $no; ?></td>
							<td><?php echo $item; ?></td>
							<td align="right"><?php echo $totalItemLists[$key]; ?></td>
							<td align="right"><?php echo $totalItemLists[$key]/$jmldata*100; ?>%</td>
						</tr>
						<?php
						}
						?>
					</table>
				</div>
				<?php 
				$size = 2;
				
				$listCombination = combination($listSupportItem, $size);
				$result = [];
				while(count($listCombination) > 0){
					$listItem = [];
					$totalItemList = countArray($listdata, $listCombination);
					?>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr class="bg-primary">
								<td colspan="4">Tabel <?php echo $size;?></td>
							</tr>
							<tr class="bg-primary">
								<td width="25">No</td>
								<td>Item Pembelian</td>
								<td>Support Total</td>
								<td>Support Persen</td>
							</tr>
							<?php 
							$no=0;
							foreach($listCombination as $key => $item){
							$no++;
							$percent = $totalItemList[$key]/$jmldata*100;
							$class = "";
							if($percent >= $minsupport){
								$class ="bg-success";
								$arr =[];
								$arr["total"] = $totalItemList[$key];
								$arr["items"]  = $item;
								$arr["item"]  = $item[0];
								$arr["key"]  = array_search($item[0], $itemList);
								$arr["value"]  = $totalItemLists[$arr["key"]];
								$result[] = $arr ;
								$listItem = array_unique(array_merge($listItem, $item), SORT_REGULAR);
							}
							?>
							<tr class="<?php echo $class;?>">
								<td><?php echo $no; ?></td>
								<td><?php echo arrayToString($item); ?></td>
								<td align="right"><?php echo $totalItemList[$key]; ?></td>
								<td align="right"><?php echo $totalItemList[$key]/$jmldata*100; ?>%</td>
							</tr>
							<?php
							}
							?>
						</table>
					</div>
					<?php	
					$listCombination =combinationArray($listCombination, $listItem, $size);
					$size++;
				}
				?>
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr class="bg-primary">
							<td colspan="4">Hasil</td>
						</tr>
						<tr class="bg-primary">
							<td width="25">No</td>
							<td>Aturan </td>
							<td>Perbandingan</td>
							<td>Persentase</td>
						</tr>
						<?php 
						$no=0;
						foreach($result as $res){
							$no++;
							$percent = $res["total"]/$res["value"]*100;
							$class = "";
							if($percent >= $minconfidence){
								$class="bg-success";
							}
							?>
							<tr class="<?php echo $class;?>">
								<td><?php echo $no;?></td>
								<td><?php echo arrayToString($res["items"]);?></td>
								<td><?php echo $res["total"]." : ".$res["value"];?></td>
								<td><?php echo $res["total"]/$res["value"]*100; ?>%</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</div>
			<?php  }?>
		</div>
	</div>
</form>	
</body>
</html>
<?php
function getItemList($listdata){
	$item=[];
	foreach($listdata as $data){
		if(strlen($data) > 0){		
			$arr = array_map('trim', explode(',', $data));
			$result = array_unique(array_merge($item, $arr), SORT_REGULAR);
			$item = $result;	
		}
	}
	return $item;
}
function getTotalEachItem($listdata){
	$listItem = getItemList($listdata);
	$result = [];
	foreach($listdata as $key => $data){
		$arr = array_map('trim', explode(',', $data));
		$countArray = array_count_values($arr);
		foreach($listItem as $keyItem => $item){
			if(!isset($result[$keyItem])){
				$result[$keyItem]=0;
			}
			$tempResult = [];
			$total = 0;
			if(in_array($item, $arr)){
				$total =$countArray[$item];
			}
			$result[$keyItem] += $total;
			//$result["item"][$key][$keyItem] = $total; 
		}
	}
	return $result;
}
function countArray($listdata, $arrItem){
	$result = [];
	foreach($listdata as $key => $data){
		$arr = array_map('trim', explode(',', $data));
		foreach($arrItem as $keyItem => $item){
			if(!isset($result[$keyItem])){
				$result[$keyItem]=0;
			}
			$total = 0;
			if(count(array_intersect($item, $arr)) == count($item)){
				$total = 1;
			}
			$result[$keyItem] += $total;
		}
	}
	return $result;
}
function combination($chars, $size, $combinations = array()) {

    # if it's the first iteration, the first set 
    # of combinations is the same as the set of characters
    if (empty($combinations)) {
        $combinations = $chars;
    }

    # we're done if we're at size 1
    if ($size == 1) {
        return $combinations;
    }

    # initialise array to put new values in
    $new_combinations = array();

    # loop through existing combinations and character set to create strings
    foreach ($combinations as $combination) {
        foreach ($chars as $char) {
			if($combination != $char){				
				$arr = [];
				$arr[] = $combination;
				$arr[] = $char;
				$cekCount = cekCombination($arr, $new_combinations, $size);
				if($cekCount){
					$new_combinations[] =  $arr;
				}
			}
        }
    }

    # call same function again for the next iteration
    return combination($chars, $size - 1, $new_combinations);

}

function combinationArray($listData, $listNewData, $size) {
	$result = [];
	foreach($listData as $data){
		if(count(array_intersect($data, $listNewData)) == $size){
			foreach($listNewData as $newData ){
				if(!in_array($newData, $data)){
					$arr = $data;
					$arr[] = $newData;
					$cekCount = cekCombination($arr, $result, $size+1);
					if($cekCount){
						$result[] = $arr;
					}
					
				}
			}
		}
	}
	return $result;
}

function cekCombination($cek, $combination =[], $size){
	foreach($combination as $data){
		if(count(array_intersect($cek, $data)) == $size){
			return false;
		}
	}
	return true;
}

function arrayToString($arr){
	$text = $arr[0];
	for($i=1;$i<count($arr);$i++){
		$text.= ", ".$arr[$i];
	}
	return $text;
}
?>