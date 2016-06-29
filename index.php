<!doctype html>
<html lang="en">
<body>

<?php 
	$conn=mysql_connect('localhost','root','') or die("SQL SERVER 数据库连接失败！"); 
	//选择数据库
	mysql_select_db('pso',$conn); 
	
	mysql_query("set character set 'utf8'");//读库
	mysql_query("set names 'utf8'");//写库
	
	//sql语句
	$sql="SELECT LIZI,GENERATION,W,C1,C2 FROM data"; 
	$result=mysql_query($sql); 
    $shuju = mysql_fetch_array($result);
	
	//laile = =

$LIZI=$shuju[0];
$GENERATION=$shuju[1];
$W=$shuju[2];
$C1=$shuju[3];
$C2=$shuju[4];

function yuejie($miaomi)
{
     $a=array();
     for ($i=0;$i<6;++$i) $a[$i] = $miaomi[$i];
     
     $yuejie = false;
     if ($a[0]+$a[3] > 20 || $a[0]+$a[3] < 5) $yuejie = true;
	 if ($a[1]+$a[4] > 20 || $a[1]+$a[4] < 4) $yuejie = true;
	 if ($a[2]+$a[5] > 20 || $a[2]+$a[5] < 3) $yuejie = true;
    
     $k;
     $ba=array(10.0,10.0,10.0);
     $k = 0;
     while ($a[0]!=0)//a[0]
     {
           --$a[0];
           $ba[$k] -= 3.0*2;
           ++$k;
           if ($k==3) $k = 0;
     }
     while ($a[2]!=0)//a[2]
     {
           --$a[2];
           $ba[$k] -= 0.56*2;
           ++$k;
           if ($k==3) $k = 0;
     }
     
     $er=array(11.0,11.0,11.0,11.0,11.0);
     $k = 0;
     while ($a[3]!=0)//a[3]
     {
           --$a[3];
           $er[$k] -= 3.1*2;
           ++$k;
           if ($k==5) $k = 0;
     }
     while ($a[4]!=0)//a[4]
     {
           --$a[4];
           $er[$k] -= 2.0*2;
           ++$k;
           if ($k==5) $k = 0;
     }
     
	 for ($i=0;$i<3;++$i)  if ($ba[$i]<0) $yuejie = true;
	 for ($i=0;$i<5;++$i)  if ($er[$i]<0) $yuejie = true;
	 
	 /*if (!$yuejie)
	 {
     	for ($i=0;$i<3;++$i)  {echo $ba[$i]; echo " ";}
     	for ($i=0;$i<5;++$i)  {echo $er[$i]; echo " ";}
	 	echo"<br>";
	 }*/
     
     return $yuejie;
}

function suiji()
{
	$k = (rand(1,100)/100);
       return $k;

}


	
	$X=array(array());
	$V=array(array());
	$pBest=array(array());
	$gBest=array();
	$bestlirun=array();
	$maxlirun = 0;
	for ($i=0;$i<$LIZI;++$i) //初始化粒子 
	{
		//位置
        do
        {
		     $X[$i][0] = 0 + rand()%(21);
		     $X[$i][1] = 0;
		     $X[$i][2] = 0 + rand()%(21);
		     $X[$i][3] = 0 + rand()%(21);
		     $X[$i][4] = 0 + rand()%(21);
		     $X[$i][5] = 0;   //$X[0][0]=3; $X[0][1]=0; $X[0][2]=9; $X[0][3]=5; $X[0][4]=5; $X[0][5]=0;
        } 
        while(yuejie($X[$i]));
		
		//速度
		{
		     $V[$i][0] = suiji()-2;
		     $V[$i][1] = 0;
		     $V[$i][2] = suiji()-2;
		     $V[$i][3] = suiji()-2;
		     $V[$i][4] = suiji()-2;
		     $V[$i][5] = 0;   //$X[0][0]=3; $X[0][1]=0; $X[0][2]=9; $X[0][3]=5; $X[0][4]=5; $X[0][5]=0;
        } 
		
		
		
		
		for ($j=0;$j<6;++$j)
		{
			$pBest[$i][$j] = $X[$i][$j]; 
		}
		
		$chengben = $X[$i][0]*30000*3.0 + $X[$i][3]*40000*3.1 + $X[$i][4]*2.0*40000 + $X[$i][2]*30000*0.56;
		$lirun = ($X[$i][0]*168+$X[$i][3]*200)*1500*0.9 + ($X[$i][4]*200)*1200*0.7 + ($X[$i][2]*168)*600*0.2 - $chengben;
		$lirun *= 2;
		$bestlirun[$i] = $lirun;
		
		if ($lirun > $maxlirun)
		{
			$maxlirun = $lirun;
			for ($j=0;$j<6;++$j)
				$gBest[$j] = $X[$i][$j];
		}
		
		/*echo "X pBest V bestlirun 分别是";
		for ($j=0;$j<6;++$j)
		{
		    echo $X[$i][$j]; echo" ";
		}
		for ($j=0;$j<6;++$j)
		{
			echo $pBest[$i][$j]; echo" ";
		}
		
		for ($j=0;$j<6;++$j)
		{
			echo $V[$i][$j]; echo" ";
		}
		echo $bestlirun[$i]; echo" ";
		
		echo"<br>";*/
		
	}  
		/*echo "gBest maxlirun 是 ";
		for ($j=0;$j<6;++$j)
		{
			echo $gBest[$j]; echo" ";
		}
		echo $maxlirun;
		echo "<br>";*/

//粒子变化
for ($cnt=0;$cnt<$GENERATION;++$cnt)
{
	for ($i=0;$i<$LIZI;++$i)
	{
		//变化
		for ($j=0;$j<6;++$j)
		{
			$V[$i][$j] = $V[$i][$j]*$W + $C1*suiji()*($pBest[$i][$j]-$X[$i][$j]) + $C2*suiji()*($gBest[$j]-$X[$i][$j]);
			$X[$i][$j] += $V[$i][$j];
			$X[$i][$j] = floor($X[$i][$j]);
			if ($X[$i][$j] < 0) $X[$i][$j] = 0;
		}
		$X[$i][1] = 0;
		$X[$i][5] = 0;
		
		//约束
		if (yuejie($X[$i]))
			for ($j=0;$j<6;++$j)
				$X[$i][$j] = $pBest[$i][$j];
		
		//评估
		$chengben = $X[$i][0]*30000*3.0 + $X[$i][3]*40000*3.1 + $X[$i][4]*2.0*40000 + $X[$i][2]*30000*0.56;
		$lirun = ($X[$i][0]*168+$X[$i][3]*200)*1500*0.9 + ($X[$i][4]*200)*1200*0.7 + ($X[$i][2]*168)*600*0.2 - $chengben;
		$lirun *= 2;
		$bestlirun[$i] = $lirun;
		
		if ($lirun > $bestlirun[$i])
		{
			$bestlirun[$i] = $lirun;
			for ($j=0;$j<6;++$j)
				$pBest[$i][$j] = $X[$i][$j];
		}
		if ($lirun > $maxlirun)
		{
			$maxlirun = $lirun;
			for ($j=0;$j<6;++$j)
				$gBest[$j] = $X[$i][$j];
		}
		
		
		
		
		/*echo "X pBest V bestlirun 分别是";
		for ($j=0;$j<6;++$j)
		{
		    echo $X[$i][$j]; echo" ";
		}
		for ($j=0;$j<6;++$j)
		{
			echo $pBest[$i][$j]; echo" ";
		}
		
		for ($j=0;$j<6;++$j)
		{
			echo $V[$i][$j]; echo" ";
		}
		echo $bestlirun[$i]; echo" ";
		
		echo"<br>";*/
	}
	
	/*echo "gBest maxlirun 是 ";
	for ($j=0;$j<6;++$j)
	{
		echo $gBest[$j]; echo" ";
	}
	echo $maxlirun;
	echo "<br>";*/
}


	
	
echo
"<html>
<head>
	<meta charset='utf-8'>
	<title>PSO算法</title>
	<link rel='stylesheet' href='bootstrap\css\bootstrap.css' type='text/css' />

  
</head>

<form id='form1' name='form1' method='post' action='set.php'> 
<body>

<table class='table table-bordered'>
  <tr align=center bgcolor=e9e9d8> 
    <td colspan='5'><div align='center'><h2>飞机航班规划问题PSO(组号20)</h2></div></td>
  </tr>
  <tr bgcolor=ffffff> 
    <td><b>粒子</b></td><td><b>代数</b></td><td><b>w</b></td><td><b>c1</b></td><td><b>c2</b></td>
  </tr>	
  <tr bgcolor=ffffff >
    <td><input type='num' name='LIZI' value=$LIZI style= 'width:50px' /></td>
	<td><input type='num' name='GENERATION' value=$GENERATION style= 'width:50px' /></td>
	<td><input type='num' name='W' value=$W style= 'width:50px' /></td>
	<td><input type='num' name='C1' value=$C1 style= 'width:50px' /></td>
	<td><input type='num' name='C2' value=$C2 style= 'width:50px' /></td>
   </tr>
  
   <tr bgcolor=ffffff> 
    <td colspan='5'><div align='center'> <input type='submit' class='btn btn-primary' name='Submit' value='提交'></div></td>
  </tr> 
  
</table>




 <script type=\"text/javascript\" src=\"js/jquery.min.js\"></script>
  <script type=\"text/javascript\" src=\"js/highcharts.js\"></script>
  <script type=\"text/javascript\" src=\"js/exporting.js\"></script>
  <script type=\"text/javascript\" src=\"js/highcharts-3d.js\"></script>


  <script>
 ﻿$(function () {

    // Give the points a 3D feel by adding a radial gradient
    Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.4,
                cy: 0.3,
                r: 0.5
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.2).get('rgb')]
            ]
        };
    });

    // Set up the chart
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            margin: 100,
            type: 'scatter',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 30,
                depth: 250,
                viewDistance: 5,

                frame: {
                    bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                    back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                    side: { size: 1, color: 'rgba(0,0,0,0.06)' }
                }
            }
        },
        title: {
            text: 'PSO算法三维散点图'
        },
        subtitle: {
            text: 'x:北京 y:上海 z:香港'
        },
        plotOptions: {
            scatter: {
                width: 20,
                height: 20,
                depth: 20
            }
        },
        yAxis: {
            min: 3,
            max: 8,
            title: null
        },
        xAxis: {
            min: 4.5,
            max: 8.5,
            gridLineWidth: 1
        },
        zAxis: {
            min: 0,
            max: 20
        },
        legend: {
            enabled: false
        },
        series: [{
            name: '粒子',
            colorByPoint: true,
            data: [";

for ($i=0;$i<$LIZI;++$i)
{
	$x = $X[$i][0]+$X[$i][3];//+suiji()*0.5;
	$y = $X[$i][1]+$X[$i][4];//+suiji()*0.5;
	$z = $X[$i][2]+$X[$i][5];//+suiji()*0.5;
	echo"[$x,$y,$z],";
}


     $k;
     $ba=array(0,0,0);
     $k = 0;
	 $a = array();
	 for ($i=0;$i<6;++$i) $a[$i] = $gBest[$i];
     while ($a[0]!=0)//a[0]
     {
           --$a[0];
           $ba[$k] += 3.0*2;
           ++$k;
           if ($k==3) $k = 0;
     }
     while ($a[2]!=0)//a[2]
     {
           --$a[2];
           $ba[$k] += 0.56*2;
           ++$k;
           if ($k==3) $k = 0;
     }
     
     $er=array(0,0,0,0,0);
     $k = 0;
     while ($a[3]!=0)//a[3]
     {
           --$a[3];
           $er[$k] += 3.1*2;
           ++$k;
           if ($k==5) $k = 0;
     }
     while ($a[4]!=0)//a[4]
     {
           --$a[4];
           $er[$k] += 2.0*2;
           ++$k;
           if ($k==5) $k = 0;
     }


echo"] 
        }]
    });


    // Add mouse events for rotation
    $(chart.container).bind('mousedown.hc touchstart.hc', function (e) {
        e = chart.pointer.normalize(e);

        var posX = e.pageX,
            posY = e.pageY,
            alpha = chart.options.chart.options3d.alpha,
            beta = chart.options.chart.options3d.beta,
            newAlpha,
            newBeta,
            sensitivity = 5; // lower is more sensitive

        $(document).bind({
            'mousemove.hc touchdrag.hc': function (e) {
                // Run beta
                newBeta = beta + (posX - e.pageX) / sensitivity;
                newBeta = Math.min(100, Math.max(-100, newBeta));
                chart.options.chart.options3d.beta = newBeta;

                // Run alpha
                newAlpha = alpha + (e.pageY - posY) / sensitivity;
                newAlpha = Math.min(100, Math.max(-100, newAlpha));
                chart.options.chart.options3d.alpha = newAlpha;

                chart.redraw(false);
            },                            
            'mouseup touchend': function () { 
                $(document).unbind('.hc');
            }
        });
    });
    
});				
	</script>
  

  
  <div id='container' style='min-width:500px;height:500px'></div>  <br>
  
<table class='table table-bordered'>
  <tr align=center bgcolor=e9e9d8> 
    <td colspan='5'><div align='center'><b>最优解</b></div></td>
  </tr>
  <tr bgcolor=ffffff> 
    <td><b>总利润</b></td><td><b>800-北京</b></td><td><b>800-香港</b></td><td><b>200-北京</b></td><td><b>200-上海</b></td>
  </tr>	
  <tr bgcolor=ffffff> 
    <td><b><span style='color:red'>$maxlirun 元</span></color></b></td><td><b>$gBest[0] 架次</b></td><td><b>$gBest[2] 架次</b></td><td><b>$gBest[3] 架次</b></td><td><b>$gBest[4] 架次</b></td>
  </tr>
</table> 

<table class='table table-bordered'>
  <tr align=center bgcolor=e9e9d8> 
    <td colspan='8'><div align='center'><b>各架飞机使用率</b></div></td>
  </tr>
  <tr bgcolor=ffffff> 
    <td><b>800-1号</b></td><td><b>800-2号</b></td><td><b>800-3号</b></td><td><b>200-1号</b></td><td><b>200-2号</b></td><td><b>200-3号</b></td><td><b>200-4号</b></td><td><b>200-5号</b></td>
  </tr>	
  <tr bgcolor=ffffff> 
    <td><b>$ba[0] h</b></td><td><b>$ba[1] h</b></td><td><b>$ba[2] h</b></td><td><b>$er[0] h</b></td><td><b>$er[1] h</b></td><td><b>$er[2] h</b></td><td><b>$er[3] h</b></td><td><b>$er[4] h</b></td>
  </tr>
</table> 


 
</body>
</html>"; 

?>