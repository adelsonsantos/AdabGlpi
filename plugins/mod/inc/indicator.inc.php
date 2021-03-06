<?php


if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

global $DB, $CFG_GLPI;

echo Html::css($CFG_GLPI["root_doc"]."/css/font-awesome.css");

//Stevenes Donato
//satisfação por tecnico
$query_sat = "
SELECT glpi_users.id, avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`, glpi_tickets_users, glpi_users
WHERE glpi_tickets.is_deleted = '0'
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets_users.tickets_id
AND `glpi_users`.id = glpi_tickets_users.users_id
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.users_id = ".$_SESSION['glpiID']." ";

$result_sat = $DB->query($query_sat) or die('erro');
$media = round($DB->result($result_sat,0,'media'),0);


//count due tickets
$sql_due = "SELECT DISTINCT COUNT(glpi_tickets.id) AS due
FROM glpi_tickets_users, glpi_tickets, glpi_users
WHERE glpi_tickets.status NOT IN (5,6)
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.due_date IS NOT NULL
AND glpi_tickets.due_date < NOW()
AND glpi_tickets_users.type = 2
AND glpi_users.id = ".$_SESSION['glpiID']." ";

$result_due = $DB->query($sql_due);

$due = $DB->result($result_due,0,'due');

if($due > 0) {
	$href_due = "".$CFG_GLPI["root_doc"]."/front/ticket.php?is_deleted=0&criteria[0][field]=5&criteria[0]
	[searchtype]=equals&criteria[0][value]=".$_SESSION['glpiID']."&criteria[1][link]=AND&criteria[1][field]=82
	&criteria[1][searchtype]=equals&criteria[1][value]=1&itemtype=Ticket&start=0";
}
else { $href_due = "#"; }

//count open tickets
$sql_cham = "SELECT DISTINCT COUNT(glpi_tickets.id) AS number
FROM glpi_tickets_users, glpi_tickets, glpi_users
WHERE glpi_tickets.status NOT IN (5,6)
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.`users_id` = glpi_users.id
AND glpi_tickets_users.type = 2
AND glpi_users.id = ".$_SESSION['glpiID']." ";

$result_cham = $DB->query($sql_cham);

$number = $DB->result($result_cham,0,'number');

if($number > 0) {
	$href_cham = $CFG_GLPI["root_doc"]."/front/ticket.php?is_deleted=0&criteria[0][field]=5
		&criteria[0][searchtype]=equals&criteria[0][value]=".$_SESSION['glpiID']."&criteria[1][link]=AND
		&criteria[1][field]=12&criteria[1][searchtype]=equals&criteria[1][value]=notold
		&itemtype=Ticket&start=0";
}
else { $href_cham = "#"; }


//select tasks
$query_task = "
SELECT glpi_tickettasks.id AS id, glpi_tickettasks.content AS content, glpi_tickettasks.tickets_id AS tid
FROM glpi_tickettasks, glpi_tickets
WHERE glpi_tickettasks.users_id_tech = ".$_SESSION['glpiID']."
AND glpi_tickettasks.state NOT IN (2)
AND glpi_tickettasks.tickets_id = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.status NOT IN (5,6)";

$res_task = $DB->query($query_task);
$num_tasks = $DB->numrows($res_task);

if($num_tasks > 0) {
	$href_tasks = $CFG_GLPI["root_doc"]."/front/ticket.php?is_deleted=0&criteria[0][field]=12&criteria[0][searchtype]=equals
	&criteria[0][value]=notclosed&criteria[1][link]=AND&criteria[1][field]=95&criteria[1][searchtype]=equals
	&criteria[1][value]=".$_SESSION['glpiID']."&itemtype=Ticket&start=0";
}
else { $href_tasks = "#"; }

//task label color
if($num_tasks <= 0)  { $label3 = 'label-success'; }
if($num_tasks >= 1  && $num_tasks <= 3) { $label3 = 'label-primary'; }
if($num_tasks >= 4  && $num_tasks <= 5) { $label3 = 'label-warning'; }
if($num_tasks > 5) { $label3 = 'label-danger'; }

//label color due
if($due == 0)  { $label = 'label-success'; }
if($due >= 1 ) { $label = 'label-danger'; }

if($number <= 0)  { $label2 = 'label-success'; }
 		if($number >= 1  && $number <= 3) { $label2 = 'label-primary'; }
if($number >= 4  && $number <= 5) { $label2 = 'label-warning'; }
if($number > 5) { $label2 = 'label-danger'; }	     


echo "
<style>
	@media screen and (max-width: 750px) {
	  #count { display:none; }
	}
</style>\n";


//tasks
echo "<ul><li id='count' class='' style='font-size:12px;' title='". _n('Ticket task','Ticket tasks',2) ."'>";
echo "<a href='".$href_tasks."' class='' data-toggle='dropdown' role='button' aria-expanded='false'>
		<i class='fa fa-tasks' style='vertical-align:bottom; font-size:15px;'></i> 
		<span class='label ".$label3."' style='font-size:12px;' >". $num_tasks. "</span></a>\n";
echo "</li>\n";

//late tickets
echo "<li id='count' class='dropdown' style='font-size:12px;' title='". __('Late') ."'>
		<a href='".$href_due."'>
		<i style='vertical-align:bottom; font-size:15px;' class='fa fa-clock-o'></i>
		<span class='label ".$label."' style='font-size:12px;'>". $due. "</span></a></li>\n";

//tickets
echo "<li id='count' class='dropdown' style='font-size:12px;' title='". _nx('ticket','Opened','Opened',2) ."'>
		<a href='".$href_cham."'>
		<i style='vertical-align:bottom; font-size:16px;' class='fa fa-ticket' ></i>
		<span class='label ".$label2."' style='font-size:12px;' >". $number. "</span></a></li>\n";


echo "<li id='count' class='' style='font-size:12px; margin-top:0px;' title='". __('Create ticket') ."'>";
echo "<a href='".$CFG_GLPI["root_doc"]."/front/ticket.form.php' style='margin-top:2px;' data-toggle='dropdown' role='button' aria-expanded='false'>
		<!-- <i class='fa fa-plus' style='vertical-align:bottom; font-size:15px;'></i>  -->
		<span class='label label-primary' style='font-size:12px;' >
			<i class='fa fa-plus' style='vertical-align:bottom; font-size:15px;'></i> 
		</span></a>\n";
echo "</li></ul>\n";
      
     
?>     
