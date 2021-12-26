<?php

function logSystem($payload=''){
    $sysLogs = fopen(__DIR__.'./logs/phpr-'.date('d_m_Y-H_i_s').'.txt', "w");
    fwrite($sysLogs, $payload);
    fclose($sysLogs);
}