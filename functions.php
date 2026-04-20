<?php

require('config.php');
function verifyAspNetIdentityPassword($password, $hashedPassword) {
    $decoded = base64_decode($hashedPassword, true);

    if ($decoded === false || strlen($decoded) < 49) {
        return false;
    }

    $formatMarker = ord($decoded[0]);

    if ($formatMarker !== 0x01) {
        return false;
    }

    $prf = unpack('N', substr($decoded, 1, 4))[1];
    $iterCount = unpack('N', substr($decoded, 5, 4))[1];
    $saltLength = unpack('N', substr($decoded, 9, 4))[1];

    $salt = substr($decoded, 13, $saltLength);
    $storedSubkey = substr($decoded, 13 + $saltLength);

    $algo = match ($prf) {
        0 => 'sha1',
        1 => 'sha256',
        2 => 'sha512',
        default => 'sha256'
    };

    $generatedSubkey = hash_pbkdf2(
        $algo,
        $password,
        $salt,
        $iterCount,
        strlen($storedSubkey),
        true
    );

    return hash_equals($storedSubkey, $generatedSubkey);
}

function hashAspNetIdentityPassword($password) {
    $prf = 1; // HMACSHA256
    $iterCount = 10000;
    $saltSize = 16;
    $subkeyLength = 32;

    $salt = random_bytes($saltSize);

    $subkey = hash_pbkdf2(
        'sha256',
        $password,
        $salt,
        $iterCount,
        $subkeyLength,
        true
    );

    $output = chr(0x01);
    $output .= pack('N', $prf);
    $output .= pack('N', $iterCount);
    $output .= pack('N', $saltSize);
    $output .= $salt;
    $output .= $subkey;

    return base64_encode($output);
}

function isLoggedIn() {
    return isset($_SESSION['role']);
}

function isGuest(): bool
{
    return !isset($_SESSION['role']) || $_SESSION['role'] == 0;
}

function isWorker(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] >= 1;
}

function isAdmin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] == 2;
}

function kysiPiibudMenuu() {
    global $yhendus;

    $kask = $yhendus->prepare("
        SELECT
            b.Id AS brandId,
            b.Name AS brandName,
            b.RegularPrice,
            b.ClientPrice,

            f.Id AS flavorId,
            f.FlavorName,
            f.Description,
            f.IsAvailable

        FROM HookahBrands b
        LEFT JOIN HookahFlavors f ON f.HookahBrandId = b.Id
        WHERE b.IsAvailable = 1
        ORDER BY b.Name ASC, f.FlavorName ASC
    ");

    $kask->execute();
    $tulemus = $kask->get_result();

    $piibud = [];

    while ($rida = $tulemus->fetch_assoc()) {

        $brandId = $rida["brandId"];

        /* Если бренд ещё не добавлен — создаём */
        if (!isset($piibud[$brandId])) {
            $piibud[$brandId] = [
                "id" => $brandId,
                "name" => $rida["brandName"],
                "description" => "", // у тебя его нет в базе
                "full_price" => $rida["RegularPrice"],
                "client_price" => $rida["ClientPrice"],
                "flavors" => []
            ];
        }

        /* Добавляем вкус (ТОЛЬКО если он доступен) */
        if (!empty($rida["flavorId"]) && (int)$rida["IsAvailable"] === 1) {
            $piibud[$brandId]["flavors"][] = [
                "id" => $rida["flavorId"],
                "name" => $rida["FlavorName"],
                "description" => $rida["Description"] ?? ""
            ];
        }
    }

    return array_values($piibud);
}

function kysiHookahBrands() {
    global $yhendus;

    $kask = $yhendus->prepare("
        SELECT Id, Name, RegularPrice, IsAvailable, ClientPrice
        FROM HookahBrands
        ORDER BY Name ASC
    ");
    $kask->execute();

    $tulemus = $kask->get_result();
    $brands = [];

    while ($rida = $tulemus->fetch_assoc()) {
        $brands[] = $rida;
    }

    return $brands;
}
function kysiPiibud($sorttulp = "Nimetus", $otsisona = '', $category = ''){
    global $yhendus;

    $lubatudtulbad = [
        "id" => "f.Id",
        "nimetus" => "f.FlavorName",
        "kategooria" => "b.Name",
        "taishind" => "b.RegularPrice",
        "kliendihind" => "b.ClientPrice",
        "staatus" => "f.IsAvailable"
    ];

    $sorttulp = strtolower($sorttulp);

    if(!array_key_exists($sorttulp, $lubatudtulbad)){
        $sorttulp = "nimetus";
    }

    $sortsql = $lubatudtulbad[$sorttulp];

    $otsisona = addslashes(stripslashes($otsisona));

    if (!isGuest()) {
        $kask = $yhendus->prepare("
            SELECT 
                f.Id,
                f.FlavorName,
                f.HookahBrandId,
                f.Description,
                b.Name,
                b.RegularPrice,
                b.ClientPrice,
                f.IsAvailable
            FROM HookahFlavors f, HookahBrands b
            WHERE (
                f.HookahBrandId=b.Id
            )
            AND (
                f.FlavorName LIKE '%$otsisona%' 
                OR b.Name LIKE '%$otsisona%' 
                OR b.RegularPrice LIKE '%$otsisona%' 
                OR b.ClientPrice LIKE '%$otsisona%' 
                OR f.IsAvailable LIKE '%$otsisona%'
            )
            ORDER BY $sortsql");
    }

    $kask->execute();
    $kask->bind_result($id, $FlavorName, $HookahBrandId, $Description, $Name, $RegularPrice, $ClientPrice, $IsAvailable);

    $hoidla = array();
    while($kask->fetch()){
        $piip = new stdClass();
        $piip->Id = $id;
        $piip->FlavorName = $FlavorName;
        $piip->HookahBrandId = $HookahBrandId;
        $piip->Description = $Description;
        $piip->Name = $Name;
        $piip->RegularPrice = $RegularPrice;
        $piip->ClientPrice = $ClientPrice;
        $piip->IsAvailable = $IsAvailable;
        array_push($hoidla, $piip);
    }
    return $hoidla;
}

function lisaPiip($flavorName, $hookahBrandId, $Description, $isAvailable) {
    global $yhendus;

    $stmt = $yhendus->prepare("
        INSERT INTO HookahFlavors (FlavorName, HookahBrandId, Description, IsAvailable)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("sisi", $flavorName, $hookahBrandId, $Description, $isAvailable);
    $stmt->execute();
    $stmt->close();
}

function muudaPiip($Id, $flavorName, $hookahBrandId, $Description, $isAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        UPDATE HookahFlavors
        SET flavorName = ?, hookahBrandId = ?, Description = ?, isAvailable = ?
        WHERE Id = ?
    ");
    $kask->bind_param("sisii", $flavorName, $hookahBrandId, $Description, $isAvailable, $Id);
    $kask->execute();
}

function muudaPiibuStaatus($Id, $isAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        UPDATE HookahFlavors
        SET IsAvailable = ?
        WHERE Id = ?
    ");
    $kask->bind_param("ii", $isAvailable, $Id);
    $kask->execute();
}

function kustutaPiip($Id){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM HookahFlavors WHERE Id=?");
    $kask->bind_param("i", $Id);
    $kask->execute();
}

function lisaBron($Name, $Date, $Time, $PeopleCount, $Contact) {
    global $yhendus;

    $stmt = $yhendus->prepare("
        INSERT INTO Reservations (Name, Date, Time, PeopleCount, Contact)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssis", $Name, $Date, $Time, $PeopleCount, $Contact);
    $stmt->execute();
    $stmt->close();
}

function lisaKlient($UserName, $Email, $Password) {
    global $yhendus;

    $role = 0;

    $hashedPassword = hashAspNetIdentityPassword($Password);

    $stmt = $yhendus->prepare("
        INSERT INTO Users (UserName, Email, Password, Role)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("sssi", $UserName, $Email, $hashedPassword, $role);
    $stmt->execute();
    $stmt->close();
}

function kysiJoogid($sorttulp = "Nimetus", $otsisona = '', $category = ''){
    global $yhendus;

    $lubatudtulbad = [
        "id" => "Id",
        "nimetus" => "Name",
        "kategooria" => "Category",
        "hind" => "Price",
        "staatus" => "IsAvailable"
    ];

    $sorttulp = strtolower($sorttulp);

    if(!array_key_exists($sorttulp, $lubatudtulbad)){
        $sorttulp = "nimetus";
    }

    $sortsql = $lubatudtulbad[$sorttulp];

    $otsisona = addslashes(stripslashes($otsisona));
    $category = addslashes(stripslashes($category));

    $joogiTingimus = "
        (
            Category='Kuumad joogid'
            OR Category='Karastusjoogid'
            OR Category='Alkohol'
            OR Category='Kokteilid'
        )
    ";

    $categoryFilter = "";
    if (!empty($category)) {
        $categoryFilter = " AND Category = '$category' ";
    }

    $kask = $yhendus->prepare("
        SELECT 
            Id,
            Name,
            Description,
            Price,
            Category,
            IsAvailable
        FROM MenuItems
        WHERE
            $joogiTingimus
            $categoryFilter
            AND (
                Name LIKE '%$otsisona%' 
                OR Description LIKE '%$otsisona%' 
                OR Category LIKE '%$otsisona%' 
                OR Price LIKE '%$otsisona%' 
                OR IsAvailable LIKE '%$otsisona%'
            )
        ORDER BY $sortsql
    ");

    $kask->execute();
    $kask->bind_result($id, $Name, $Description, $Price, $Category, $IsAvailable);

    $hoidla = array();
    while($kask->fetch()){
        $jook = new stdClass();
        $jook->Id = $id;
        $jook->Name = $Name;
        $jook->Description = $Description;
        $jook->Price = $Price;
        $jook->Category = $Category;
        $jook->IsAvailable = $IsAvailable;
        array_push($hoidla, $jook);
    }
    return $hoidla;
}

function lisaJook($Name, $Description, $Price, $Category, $IsAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        INSERT INTO MenuItems (Name, Description, Price, Category, IsAvailable)
        VALUES (?, ?, ?, ?, ?)
    ");
    $kask->bind_param("ssdsi", $Name, $Description, $Price, $Category, $IsAvailable);
    $kask->execute();
}

function muudaJook($Id, $Name, $Description, $Price, $Category, $IsAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        UPDATE MenuItems
        SET Name = ?, Description = ?, Price = ?, Category = ?, IsAvailable = ?
        WHERE Id = ?
    ");
    $kask->bind_param("ssdsii", $Name, $Description, $Price, $Category, $IsAvailable, $Id);
    $kask->execute();
}

function muudaJoogiStaatus($Id, $IsAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        UPDATE MenuItems
        SET IsAvailable = ?
        WHERE Id = ?
    ");
    $kask->bind_param("ii", $IsAvailable, $Id);
    $kask->execute();
}

function kustutaJook($Id){
    global $yhendus;
    $kask = $yhendus->prepare("DELETE FROM MenuItems WHERE Id=?");
    $kask->bind_param("i", $Id);
    $kask->execute();
}

function kysiJoogiKategooriad() {
    global $yhendus;

    $kask = $yhendus->prepare("
        SELECT DISTINCT Category
        FROM MenuItems
        WHERE 
            Category='Kuumad joogid'
            OR Category='Karastusjoogid'
            OR Category='Alkohol'
            OR Category='Kokteilid'
        ORDER BY Category
    ");
    $kask->execute();
    $tulemus = $kask->get_result();

    $categories = [];
    while ($rida = $tulemus->fetch_assoc()) {
        $categories[] = $rida;
    }

    return $categories;
}

function kysiSnakid($sorttulp = "Nimetus", $otsisona = '', $category = ''){
    global $yhendus;

    $lubatudtulbad = [
        "id" => "Id",
        "nimetus" => "Name",
        "kategooria" => "Category",
        "hind" => "Price",
        "staatus" => "IsAvailable"
    ];

    $sorttulp = strtolower($sorttulp);

    if(!array_key_exists($sorttulp, $lubatudtulbad)){
        $sorttulp = "nimetus";
    }

    $sortsql = $lubatudtulbad[$sorttulp];

    $otsisona = addslashes(stripslashes($otsisona));
    $category = addslashes(stripslashes($category));

    $snakiTingimus = "
        (
            Category='Snäkid'
        )
    ";
    $categoryFilter = "";
    if (!empty($category)) {
        $categoryFilter = " AND Category = '$category' ";
    }

    $kask = $yhendus->prepare("
        SELECT 
            Id,
            Name,
            Description,
            Price,
            Category,
            IsAvailable
        FROM MenuItems
        WHERE
            $snakiTingimus
            AND (
                Name LIKE '%$otsisona%' 
                OR Description LIKE '%$otsisona%' 
                OR Category LIKE '%$otsisona%' 
                OR Price LIKE '%$otsisona%' 
                OR IsAvailable LIKE '%$otsisona%'
            )
        ORDER BY $sortsql
    ");

    $kask->execute();
    $kask->bind_result($id, $Name, $Description, $Price, $Category, $IsAvailable);

    $hoidla = array();
    while($kask->fetch()){
        $snak = new stdClass();
        $snak->Id = $id;
        $snak->Name = $Name;
        $snak->Description = $Description;
        $snak->Price = $Price;
        $snak->Category = $Category;
        $snak->IsAvailable = $IsAvailable;
        array_push($hoidla, $snak);
    }
    return $hoidla;
}

function lisaSnak($Name, $Description, $Price, $Category, $IsAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        INSERT INTO MenuItems (Name, Description, Price, Category, IsAvailable)
        VALUES (?, ?, ?, ?, ?)
    ");
    $kask->bind_param("ssdsi", $Name, $Description, $Price, $Category, $IsAvailable);
    $kask->execute();
}

function muudaSnak($Id, $Name, $Description, $Price, $Category, $IsAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        UPDATE MenuItems
        SET Name = ?, Description = ?, Price = ?, Category = ?, IsAvailable = ?
        WHERE Id = ?
    ");
    $kask->bind_param("ssdsii", $Name, $Description, $Price, $Category, $IsAvailable, $Id);
    $kask->execute();
}

function muudaSnakiStaatus($Id, $IsAvailable) {
    global $yhendus;

    $kask = $yhendus->prepare("
        UPDATE MenuItems
        SET IsAvailable = ?
        WHERE Id = ?
    ");
    $kask->bind_param("ii", $IsAvailable, $Id);
    $kask->execute();
}

function kustutaSnak($Id){
    global $yhendus;
    $kask = $yhendus->prepare("DELETE FROM MenuItems WHERE Id=?");
    $kask->bind_param("i", $Id);
    $kask->execute();
}

function kysiSnakiKategooriad() {
    global $yhendus;

    $kask = $yhendus->prepare("
        SELECT DISTINCT Category
        FROM MenuItems
        WHERE 
            Category='Snäkid'
        ORDER BY Category
    ");
    $kask->execute();
    $tulemus = $kask->get_result();

    $categories = [];
    while ($rida = $tulemus->fetch_assoc()) {
        $categories[] = $rida;
    }

    return $categories;
}
?>




