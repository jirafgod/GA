<?
ini_set('memory_limit', '160000000000');
$data = [];

$count_par = 10000;
$pokolen = 100;
$max = 0;
for ($i=0; $i < 50; $i++) { 
    for ($j=0; $j < 50; $j++) { 
        $data[$i][$j]=rand(1,48);
    }
}
$new_children = [];
for ($o=0; $o < $count_par; $o++) { 
    for ($p=0; $p < 50; $p++) { 
        $new_children[$o][$p] = GenChr($new_children,$o,$p);
    }
}
for ($i=0; $i < $pokolen; $i++) { 
    $parrents = $new_children;
    $new_children = [];
    echo "Поколение №".$i."\n";
        $mutant = 0;
        $die = 0;
        if(count($parrents)<2){
            echo "\n\nОй, все вымерли\n\n";
            break;
        }
    for ($c=0; $c < count($parrents)*2; $c+=2) { 
        $para = GenChld(count($parrents));
        $children[$c] = array_merge(array_slice($parrents[$para[0]],0,45),array_slice($parrents[$para[1]],45,50));
        $children[$c][25] = $children[$c][24];  
        $children[$c+1] = array_merge(array_slice($parrents[$para[1]],0,45),array_slice($parrents[$para[0]],45,50));
        $children[$c+1][25] = $children[$c][24];  
    
        for ($k=0; $k < 2; $k++) { 
            $new_ch = array_diff(range(0,49),$children[$c+$k]);
            $new_chl = array_unique($children[$c+$k]);
            array_splice($new_chl, rand(1,48), 0, $new_ch);

            $new_ch = array_unique($new_chl);        
            if(count($new_ch)!=50){
                $deff++;
            }
            if(rand(1,100) == 42){
                $r = rand(1,48);
                $nr = rand(1,48);
                $trash = $new_ch[$r];
                $new_ch[$r] = $new_ch[$nr];
                $new_ch[$nr] = $trash;
                $mutant++;
            }
            if(count($new_ch)==50 && (GetLen($new_ch) < GetLen($parrents[$para[0]]) || GetLen($new_ch) < GetLen($parrents[$para[1]]) )){
                $new_children[] = $new_ch;
            }else{
                $die++;
            }
        }
    }
    $all_len = [];
    foreach ($new_children as $key => $child) {
        $all_len[$key] = GetLen($child);
    }

    echo "Всего потомков ".count($new_children)."\n";
    echo "У нас мутировало ".$mutant."\n";
    echo "Умерло ".$die."\n";
    echo "Самый короткий путь ".min($all_len)."\n\n";
}

print_r($new_children[array_search(min($all_len),$all_len)]);

function GenChr($parrents,$o,$p)
{
    $data = [];
    if($p==0){
        $j=0;
    }elseif($p==49){
        $j=49;
    }else{
        while (1) {
            $j = rand(1,48);
            if(!in_array($j,$parrents[$o]))break;
        }
    }
    return $j;
}
function GenChld($count)
{
    $data = [];
    while (1) {
        $i = rand(0,$count-1);
        $j = rand(0,$count-1);
        if($i!=$j)break;
    }
    return [$i,$j];
}

function GetLen($new_children)
{
    global $data;
    $len = 0;
    for ($i=1; $i < 50; $i++) { 
        $len += $data[$new_children[$i-1]][$new_children[$i]];
    }
    return $len;
}

?>