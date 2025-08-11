<?php

// Replace file operations in app/io/route/admin/lang.php
return function () {
    $currentLang = $_GET['lang'] ?? 'fra';

    if ($_POST) {
        $content = $_POST['content'] ?? [];

        $prep = qp(
            db(),
            "UPDATE lang SET msgstr = ? WHERE code = ? AND msgid = ?"
        );

        foreach ($content as $msgid => $msgstr) 
            $prep->execute([$msgstr, $currentLang, $msgid]);
        

        http_out(200, '', ['Location' => "?lang=$currentLang"]);
    }

    $content = qp(db(), "SELECT msgid, msgstr FROM lang WHERE code = ?", [$currentLang])
        ->fetchAll(PDO::FETCH_KEY_PAIR);

    // Group by section prefix (unchanged)
    $sections = [];
    foreach ($content as $msgid => $msgstr) {
        [$section] = explode('.', $msgid, 2) + ['misc'];
        $sections[$section][] = ['key' => $msgid, 'value' => $msgstr];
    }

    return compact('languages', 'currentLang', 'sections', 'content');
};
