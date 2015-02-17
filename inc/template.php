<template id="leser_template">
		<div class="contain leser" id="leser_contain{{ID}}"> 
			<dl>
				<dt><a class="lesername" href="./leser.php?Leserid={{ID}}">{{Name}}, {{Vorname}}</a></dt>
				<dd><a class="leserdata">{{Strasse}} {{Hausnr}}<a></dd>
				<dd><a class="label leserdata">Kürzel: </a><a class="leserdata">{{Ausweiscode}}</a> <a class="label leserdata">Klasse:</a> <a class="leserdata">{{Klasse}}</a></dd>
			</dl>
			<div class="symbols">
				<a class="leser_symbol"  href="./inc/formular_leser.php?leserid={{ID}}"><img src="./img/gruppe_{{Gruppe}}.png"></a>
				{{#if info}}<a class="info_symbol" href="./inc/formular_info.php?leserid={{ID}}" title="Info über {{Vorname}} {{Name}} "><img id="info_{{ID}}" src="./img/info.png"></a>
				{{else}}<a class="info_symbol" href="./inc/formular_info.php?leserid={{ID}}" title="Info über {{Vorname}} {{Name}} "><img id="info_{{ID}}" src="./img/info_grau.png"></a>{{/if}}
				<a class="schloss_symbol" data-leserid="{{ID}}"><img id="schloss_{{ID}}" title="Leser sperren/entsperren" src="./img/gesperrt_{{gesperrt}}.png"></a>
				
			</div>
		</div>
	</template>
	<template id="buch_template">
		<div class="contain buch" id="buch_contain{{ID}}"> 
			<dl>
				{{#if Buchreihe}}<dt><a class="buchtitel"> {{Buchreihe}} - {{Titel}} ({{Jahr}})</a></dt>
				{{else}}<dt><a class="buchtitel">{{Titel}} ({{Jahr}})</a></dt>{{/if}}
				<dd><a class="label buchdata">von </a><a class="buchdata">{{Autor}}</a></dd>
				<dd><a class="label buchdata">Verlag: </a><a class="buchdata"> {{Verlag}}, &nbsp{{Beschreibung}}</a></dd>
				<dd><a class="label buchdata">ISBN:</a><a class="buchdata">{{ISBN}}</a><a class="label buchdata">&nbsp Signatur:</a><a class="buchdata"> {{Signatur}} </a></dd>
										 				
			</dl>
			<div class="symbols">
				<a class="book_symbol"  href="./inc/formular_buch.php?buchid={{ID}}"><img title="Buch ändern" src="./img/buch.png"></a>
				{{#if info}}<a class="info_symbol" href="./inc/formular_info.php?buchid={{ID}}" title="Info über {{Titel}}"><img id="info_{{ID}}" src="./img/info.png"></a>
				{{else}}<a class="info_symbol" href="./inc/formular_info.php?buchid={{ID}}" title="Info über {{Titel}}"><img id="info_{{ID}}" src="./img/info_grau.png"></a>{{/if}}
				{{#if Ausleiher}}<a href="./buch.php?Buchid={{ID}}"><img src="./img/rot.png" title='Hier kannst du sehen wer da buch bereits ausgeliehen hatte'></a>
				{{else}}<a href="./buch.php?Buchid={{ID}}"><img src="./img/grün.png" title='Hier kannst du sehen wer da buch bereits ausgeliehen hatte'></a>{{/if}}
			</div>
						

				{{#if Ausleiher }}
					<p>Ausgeliehen am {{Ausleihdatum}} bis zum <span id="rdatum_{{ID}}">{{Rückgabedatum}} </span> {{#if Vorname}} von <a href="./leser.php?Leserid={{Ausleiher}}">{{Vorname}} {{Name}}</a></p>{{/if}}
					{{#ifCond <?php if (isset($_SESSION['LESERID'])) echo $_SESSION['LESERID']; else echo "0"; ?> "!==" 0 }}
					<div id="buttons" class="hide">
						{{#ifCond Ausleiher "==" <?php if (isset($_SESSION['LESERID'])) echo $_SESSION['LESERID']; else echo "0"; ?>}}
    						<a class="back_button" data-buchid="{{ID}}" data-leserid="{{Ausleiher}}"><button class="button">Zurückgeben</button></a> 
							<a class="ver_button" data-buchid="{{ID}}" data-leserid="{{Ausleiher}}"><button class="button">Verlängern</button></a>
						{{else}}
    						<a class="vormerk_button" data-buchid="{{ID}}" data-leserid="<?php if (isset($_SESSION['LESERID'])) echo $_SESSION['LESERID']; else echo "0"; ?>"><button class="button">Vormerken</button></a> 
						{{/ifCond}}	
						{{else}}
						<a class="delete_button" data-buchid="{{ID}}" ><button title="Buch löschen" class="delete_btn">X</button></a>
					{{/ifCond}}	

						
					</div>
				{{else}}
					<p>Status: nicht ausgeliehen</p>
					{{#ifCond <?php if (isset($_SESSION['LESERID'])) echo $_SESSION['LESERID']; else echo "0"; ?> "!==" 0 }}
					<div id="buttons" class="hide">
						<a class="ausleih_button" data-buchid="{{ID}}" data-leserid="<?php if (isset($_SESSION['LESERID'])) echo $_SESSION['LESERID']; else echo "0"; ?>"><button class=button>Ausleihen</button></a>
					</div>
					{{else}}
						<a class="delete_button" data-buchid="{{ID}}" ><button title="Buch löschen" class="delete_btn">X</button></a> 
					{{/ifCond}}
				{{/if}}
		</div>
	</template>