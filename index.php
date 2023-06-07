<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>pokeapi</title>
</head>
<body>
    <header>
        <h1>ポケモン図鑑</h1>
    </header>

        
    <?php
        if(isset($_GET["prev"])){
            $page = $_GET["page"] - 10;
        } elseif(isset($_GET["next"])){
            $page = $_GET["page"] + 10;
        }else{
            $page = 0;
        }

        if(isset($_GET["pull"])) {
            $limit_page = $_GET["pull"];
        } else {
            $limit_page = 10;
        }

    /** PokeAPI のデータを取得する(id=11から20のポケモンのデータ) */
    $url = "https://pokeapi.co/api/v2/pokemon/?limit={$limit_page}&offset={$page}";
    $response = file_get_contents($url);
    // レスポンスデータは JSON 形式なので、デコードして連想配列にする
    $data = json_decode($response, true);
    // 取得結果をループさせてポケモンの名前を表示する
    print("<pre class='pokemon_wrap'>");
    foreach($data['results'] as $key => $value){
        $URL = $value['url'];
        $Response = file_get_contents($URL);
        $Data = json_decode($Response, true);


    //日本語用＝＝＝＝＝
    $e_name = $Data['name'];
    $n_url = "https://pokeapi.co/api/v2/pokemon-species/$e_name";
    $n_response = file_get_contents($n_url);
    // レスポンスデータは JSON 形式なので、デコードして連想配列にする
    $n_data = json_decode($n_response, true);
    //＝＝＝＝＝＝＝＝＝



    $e_type = $Data["types"]["0"]["type"]["name"];
    $type_url  = "https://pokeapi.co/api/v2/type/$e_type";
    $type_response = file_get_contents($type_url);
    $type_data = json_decode($type_response, true);

    print("<div class='pokemon_desc'>");
        $img_url = ($Data['sprites']['front_default']); // 正面向きのイメージ
        echo "<img src='{$img_url}'>"; 
        echo "<br>";
        echo "名前：" . $n_data["names"]["0"]["name"]; // 名前
        echo "<br>";
        echo "タイプ：" . $type_data["names"]["0"]["name"];
        echo "<br>";
        echo "高さ：" . ($Data['height']); // たかさ
        echo "<br>";
        echo "重さ：" . ($Data['weight']); // おもさ
        echo "<br>";




    print("</div>");
    }
    
    print("</pre>");



    // ページネーション


    echo <<< _FORM_
    <form action="index.php">
        <input type="submit" name="prev" value="前へ">
        <input type="submit" name="next" value="次へ">
        <input type="hidden" name="page" value="{$page}">

        <div class="limit_page">
            <select name="pull">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
            <input type="submit" value="表示件数">
        </div>
    </form>
    _FORM_;

    ?>
</body>
</html>