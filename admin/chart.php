<?php 
include('../include/include_admin.php');

IsAdmin();

$bar = array();
	
$today=getdate();
$y=$today["year"]; $m=$today["mon"];
	
for ($i=1; $i<=10; $i+=2)
{
	$SQLQuery="Select * from tbl_visitor_v where v_year='$y' and v_month='$m'";
	$rs_run=$g_dbconn->Execute($SQLQuery);
		
	if ($rs_run->EOF)
	{
		$bar[$i]=$m; $bar[$i+1]=0;
	} else {
		$bar[$i]=$m; $bar[$i+1]=$rs_run->Fields("v_value");
	}
		
	$m--;
	if ($m==0) { $m=12; $y--; } 
	
}
	
?>
<graph caption='' bgColor="f1f1f1" canvasBorderThickness="0" subcaption='' xAxisName='' yAxisMinValue='' yAxisName='' decimalPrecision='0' formatNumberScale='0' numberPrefix='' showNames='1' showValues='0'  showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' baseFontColor="666666" thousandSeparator="" chartLeftMargin="5" chartRightMargin="10" showLimits="0">
<?php
for ($i=10; $i>=1; $i-=2) {
?>
<set name='<?=MonthToWord($bar[$i-1])?>' value='<?=abs($bar[$i])?>' hoverText='<?=MonthToWord($bar[$i-1])?>'/>
<?php } ?>
</graph>