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

function isAdmin(): bool
{
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
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

    if (isAdmin()) {
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

function kustutaPiip($Id){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM HookahFlavors WHERE Id=?");
    $kask->bind_param("i", $Id);
    $kask->execute();
}

?>




