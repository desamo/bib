<?php return function ($in, $debugopt = 1) {
    $cx = array(
        'flags' => array(
            'jstrue' => false,
            'jsobj' => false,
            'spvar' => false,
            'prop' => false,
            'method' => false,
            'mustlok' => false,
            'echo' => false,
            'debug' => $debugopt,
        ),
        'constants' =>  array(
            'DEBUG_ERROR_LOG' => 1,
            'DEBUG_ERROR_EXCEPTION' => 2,
            'DEBUG_TAGS' => 4,
            'DEBUG_TAGS_ANSI' => 12,
            'DEBUG_TAGS_HTML' => 20,
        ),
        'helpers' => array(),
        'blockhelpers' => array(),
        'hbhelpers' => array(),
        'partials' => array(),
        'scopes' => array(),
        'sp_vars' => array('root' => $in),
'funcs' => array(
    'miss' => function ($cx, $v) {
        $e = "LCRun3: $v is not exist";
        if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
            error_log($e);
            return;
        }
        if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
            throw new Exception($e);
        }
    },
    'enc' => function ($cx, $var) {
        return htmlentities($cx['funcs']['raw']($cx, $var), ENT_QUOTES, 'UTF-8');
    },
    'debug' => function ($v, $f, $cx) {
        $params = array_slice(func_get_args(), 2);
        $r = call_user_func_array((isset($cx['funcs'][$f]) ? $cx['funcs'][$f] : "LCRun3::$f"), $params);

        if ($cx['flags']['debug'] & $cx['constants']['DEBUG_TAGS']) {
            $ansi = $cx['flags']['debug'] & ($cx['constants']['DEBUG_TAGS_ANSI'] - $cx['constants']['DEBUG_TAGS']);
            $html = $cx['flags']['debug'] & ($cx['constants']['DEBUG_TAGS_HTML'] - $cx['constants']['DEBUG_TAGS']);
            $cs = ($html ? (($r !== '') ? '<!!--OK((-->' : '<!--MISSED((-->') : '')
                  . ($ansi ? (($r !== '') ? "\033[0;32m" : "\033[0;31m") : '');
            $ce = ($html ? '<!--))-->' : '')
                  . ($ansi ? "\033[0m" : '');
            switch ($f) {
                case 'sec':
                case 'ifv':
                case 'unl':
                case 'wi':
                    if ($r == '') {
                        if ($ansi) {
                            $r = "\033[0;33mSKIPPED\033[0m";
                        }
                        if ($html) {
                            $r = '<!--SKIPPED-->';
                        }
                    }
                    return "$cs{{#{$v}}}$ce{$r}$cs{{/{$v}}}$ce";
                default:
                    return "$cs{{{$v}}}$ce";
            }
        } else {
            return $r;
        }
    },
    'ifvar' => function ($cx, $v) {
        return !is_null($v) && ($v !== false) && ($v !== 0) && ($v !== 0.0) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
    },
    'raw' => function ($cx, $v) {
        if ($v === true) {
            if ($cx['flags']['jstrue']) {
                return 'true';
            }
        }

        if (($v === false)) {
            if ($cx['flags']['jstrue']) {
                return 'false';
            }
        }

        if (is_array($v)) {
            if ($cx['flags']['jsobj']) {
                if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                    return '[object Object]';
                } else {
                    $ret = array();
                    foreach ($v as $k => $vv) {
                        $ret[] = $cx['funcs']['raw']($cx, $vv);
                    }
                    return join(',', $ret);
                }
            } else {
                return 'Array';
            }
        }

        return "$v";
    },
)

    );
    
    return '<div class="contain leser" id="leser_contain'.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'">   			<dl>  				<dt><a class="lesername" href="./leser.php?Leserid='.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'">'.$cx['funcs']['debug']('[Name]', 'enc', $cx, ((isset($in['Name']) && is_array($in)) ? $in['Name'] : $cx['funcs']['miss']($cx, '[Name]'))).', '.$cx['funcs']['debug']('[Vorname]', 'enc', $cx, ((isset($in['Vorname']) && is_array($in)) ? $in['Vorname'] : $cx['funcs']['miss']($cx, '[Vorname]'))).'</a></dt>  				<dd><a class="leserdata">'.$cx['funcs']['debug']('[Strasse]', 'enc', $cx, ((isset($in['Strasse']) && is_array($in)) ? $in['Strasse'] : $cx['funcs']['miss']($cx, '[Strasse]'))).' '.$cx['funcs']['debug']('[Hausnr]', 'enc', $cx, ((isset($in['Hausnr']) && is_array($in)) ? $in['Hausnr'] : $cx['funcs']['miss']($cx, '[Hausnr]'))).'<a></dd>  				<dd><a class="label leserdata">Kürzel: </a><a class="leserdata">'.$cx['funcs']['debug']('[Ausweiscode]', 'enc', $cx, ((isset($in['Ausweiscode']) && is_array($in)) ? $in['Ausweiscode'] : $cx['funcs']['miss']($cx, '[Ausweiscode]'))).'</a> <a class="label leserdata">Klasse:</a> <a class="leserdata">'.$cx['funcs']['debug']('[Klasse]', 'enc', $cx, ((isset($in['Klasse']) && is_array($in)) ? $in['Klasse'] : $cx['funcs']['miss']($cx, '[Klasse]'))).'</a></dd>  			</dl>  			<div class="symbols">  				<a class="leser_symbol"  href="./inc/formular_leser.php?leserid='.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'"><img src="./img/gruppe_'.$cx['funcs']['debug']('[Gruppe]', 'enc', $cx, ((isset($in['Gruppe']) && is_array($in)) ? $in['Gruppe'] : $cx['funcs']['miss']($cx, '[Gruppe]'))).'.png"></a>  				'.(($cx['funcs']['debug']('[info]', 'ifvar', $cx, ((isset($in['info']) && is_array($in)) ? $in['info'] : $cx['funcs']['miss']($cx, '[info]')))) ? '<a class="info_symbol" href="./inc/formular_info.php?leserid='.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'" title="Info über '.$cx['funcs']['debug']('[Vorname]', 'enc', $cx, ((isset($in['Vorname']) && is_array($in)) ? $in['Vorname'] : $cx['funcs']['miss']($cx, '[Vorname]'))).' '.$cx['funcs']['debug']('[Name]', 'enc', $cx, ((isset($in['Name']) && is_array($in)) ? $in['Name'] : $cx['funcs']['miss']($cx, '[Name]'))).' "><img id="info_'.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'" src="./img/info.png"></a>  				' : '<a class="info_symbol" href="./inc/formular_info.php?leserid='.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'" title="Info über '.$cx['funcs']['debug']('[Vorname]', 'enc', $cx, ((isset($in['Vorname']) && is_array($in)) ? $in['Vorname'] : $cx['funcs']['miss']($cx, '[Vorname]'))).' '.$cx['funcs']['debug']('[Name]', 'enc', $cx, ((isset($in['Name']) && is_array($in)) ? $in['Name'] : $cx['funcs']['miss']($cx, '[Name]'))).' "><img id="info_'.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'" src="./img/info_grau.png"></a>').'  				<a class="schloss_symbol" data-leserid="'.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'"><img id="schloss_'.$cx['funcs']['debug']('[ID]', 'enc', $cx, ((isset($in['ID']) && is_array($in)) ? $in['ID'] : $cx['funcs']['miss']($cx, '[ID]'))).'" title="Leser sperren/entsperren" src="./img/gesperrt_'.$cx['funcs']['debug']('[gesperrt]', 'enc', $cx, ((isset($in['gesperrt']) && is_array($in)) ? $in['gesperrt'] : $cx['funcs']['miss']($cx, '[gesperrt]'))).'.png"></a>  				  			</div>  		</div>';
}
?>