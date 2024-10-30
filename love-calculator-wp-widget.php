<?php
/*
Plugin Name: Love Calculator
Plugin URI: http://www.calculator.net/projects/love-calculator-widget.php
Description: This love calculator evaluates the compatibility of two people based on names. The score range from 0%-100%. The higher the score the better, eg. Bill Clinton vs. Monica Lewinsky has a score of 90%. This calculator can be inserted either to the sidebar or into the post, but not both. Install "Love Calculator" through the WordPress admin menu of Appearance or Design and then widgets to add to the sidebar. Place [calculatornet_love_calculator] in the content to insert into a post.
Author: calculator.net
Version: 1.2
Author URI: http://www.calculator.net
License: GNU GPL see http://www.gnu.org/licenses/licenses.html#GPL
*/

class calculatornet_love_calculator {

    function calc_init() {
    	$class_name = 'calculatornet_love_calculator';
    	$calc_title = 'Love Calculator';
    	$calc_desc = 'Evaluates the compatibility of two people based on names.';

    	if (!function_exists('wp_register_sidebar_widget')) return;

    	wp_register_sidebar_widget(
    		$class_name,
    		$calc_title,
    		array($class_name, 'calc_widget'),
            array(
            	'classname' => $class_name,
            	'description' => $calc_desc
            )
        );

    	wp_register_widget_control(
    		$class_name,
    		$calc_title,
    		array($class_name, 'calc_control'),
    	    array('width' => '100%')
        );

        add_shortcode(
        	$class_name,
        	array($class_name, 'calc_shortcode')
        );
    }

    function calc_display($is_widget, $args=array()) {
    	if($is_widget){
    		extract($args);
			$options = get_option('calculatornet_love_calculator');
			$title = $options['title'];
			$output[] = $before_widget . $before_title . $title . $after_title;
		}


		$output[] = '<div style="margin-top:5px;">
			<script type="text/javascript">
			function gObj(obj) {
				var theObj;
				if(document.all){
					if(typeof obj=="string"){
						return document.all(obj);
					}else{
						return obj.style;
					}
				}
				if(document.getElementById){
					if(typeof obj=="string"){
						return document.getElementById(obj);
					}else{
						return obj.style;
					}
				}
				return null;
			}
			function trimAll(sString){
				while (sString.substring(0,1) == " "){
					sString = sString.substring(1, sString.length);
				}
				while (sString.substring(sString.length-1, sString.length) == " "){
					sString = sString.substring(0,sString.length-1);
				}
				return sString;
			}
			function showquicklovemsg(inStr, isError){
				if (isError) inStr = "<font color=red>" + inStr + "</font>";
				gObj("lovecoutput").innerHTML = inStr;
			}
			function getNum(inChar){
				outputNum = 0;
				for (i=0;i<inChar.length;i++){
					outputNum += inChar.charCodeAt(i);
				}
				return outputNum;
			}
			function lovecalc(){
				showquicklovemsg("calculating...",true);
				cnameone = trimAll(gObj("cnameone").value);
				cnametwo = trimAll(gObj("cnametwo").value);
				if (cnameone.length<1){
					showquicklovemsg("please provide name one",true);
					return;
				}else if (cnametwo.length<1){
					showquicklovemsg("please provide name two",true);
					return;
				}
				cnameone = cnameone.toLowerCase();
				cnametwo = cnametwo.toLowerCase();
				totalNum = getNum(cnameone) * getNum(cnametwo);
				finalScore = totalNum % 100;

				finalScore = "<font color=green><b>compatibility: " + finalScore + "%</b></font>";
				showquicklovemsg(finalScore, false);
			}
			</script>

			<!-- Edit the following to change the look and feel of this calculator -->
			<style>
				#calinputtablelove{
					border:0;
				}
				#calinputtablelove td{
					border:0;
				}
			</style>
			<table id="calinputtablelove">
				<form>
				<tr>
					<td>Name of Person 1:<br><input type="text" name="cnameone" size="20" id="cnameone"></td>
				<tr>
				<tr>
					<td>Name of Person 2:<br><input type="text" name="cnametwo" size="20" id="cnametwo"></td>
				<tr>
				<tr>
					<td><input type="button" value="Calculate" onclick="lovecalc()"></td>
				<tr>
				<tr>
					<td><div id="lovecoutput"></div></td>
				<tr>
				<tr>
					<td align="right">by <a href="http://www.calculator.net" rel="nofollow">calculator.net</a></td>
				</tr>
				</form>
			</table>
		</div>';
    	$output[] = $after_widget;
    	return join($output, "\n");
    }

	function calc_control() {
		$class_name = 'calculatornet_love_calculator';
		$calc_title = 'Love Calculator';

	    $options = get_option($class_name);

		if (!is_array($options)) $options = array('title'=>$calc_title);

		if ($_POST[$class_name.'_submit']) {
			$options['title'] = strip_tags(stripslashes($_POST[$class_name.'_title']));
			update_option($class_name, $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);

		echo '<p>Title: <input style="width: 180px;" name="'.$class_name.'_title" type="text" value="'.$title.'" /></p>';
		echo '<input type="hidden" name="'.$class_name.'_submit" value="1" />';
	}

    function calc_shortcode($args, $content=null) {
        return calculatornet_love_calculator::calc_display(false, $args);
    }

    function calc_widget($args) {
        echo calculatornet_love_calculator::calc_display(true, $args);
    }
}

add_action('widgets_init', array('calculatornet_love_calculator', 'calc_init'));

?>