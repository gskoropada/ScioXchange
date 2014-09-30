<div id='admin_pane'>
<label>Records per page </label><select id='numOfRows'>
	<option name='10r' value='10'>10</option>
	<option name='20r' value='20'>20</option>
	<option name='50r' value='50'>50</option>
</select>
<p>Total users: <span id='total_users'></span></p>
<p><span id='previous' class='click_option'><img src='images/back_arrow.png' class='icon' title='Previous' /></span><span id='next' class='click_option'><img src='images/forward_arrow.png' class='icon' title='Next' /></span></p>
<select id='bulk_action'>
	<option value=''>Bulk actions...</option>
	<option value='bd'>Delete</option>
	<option value='ba'>Activate</option>
	<option value='bda'>Deactivate</option>
	<option value='bm'>Mark as moderated</option>
	<option value='bum'>Remove moderation</option>
</select>
<button id="btnBulkGo">Go...</button>
<span id="filtStatus"></span>
<table>
<thead>
<tr>
	<th><input type='checkbox' id='select_all'></th>
	<th id='chUserID' onclick="toggleSort('ID');" class='click_option'>User ID<span id='sort'></span><img src='images/filter_icon.png' class='icon' onclick="filter('ID');"/></th>
	<th id='chEmail' onclick="toggleSort('EML');" class='click_option'>Email<span id='sort'></span><img src='images/filter_icon.png' class='icon' onclick="filter('EML');"/></th>
	<th id='chScreenName' onclick="toggleSort('SCN');" class='click_option'>Screen Name<span id='sort'></span><img src='images/filter_icon.png' class='icon' onclick="filter('SCN');"/></th>
	<th id='chRole' onclick="toggleSort('ROLE');" class='click_option'>Role<span id='sort'></span><img src='images/filter_icon.png' class='icon' onclick="filter('ROLE');"/></th>
	<th id='chReputation' onclick="toggleSort('REP');" class='click_option'>Reputation<span id='sort'></span></th>
	<th id='chActive' onclick="toggleSort('ACT');" class='click_option'>Active?<span id='sort'></span><img src='images/filter_icon.png' class='icon' onclick="filter('ACT');"/></th>
	<th id='chMod' onclick="toggleSort('MOD');" class='click_option'>Moderated?<span id='sort'></span><img src='images/filter_icon.png' class='icon' onclick="filter('MOD');"/></th>
	<th>Options</th>
</tr>
</thead>
<tbody id='user_list'>
</tbody>
</table>
</div>