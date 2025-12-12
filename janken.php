<?php

// 変数の初期化
$user_hand = '';
$computer_hand = '';
$result = '';
$message = '下のフォームから手を選んで「勝負！」ボタンを押してください。';

// じゃんけんの手の定義 (0: グー, 1: チョキ, 2: パー)
$hands = [
    0 => 'グー',
    1 => 'チョキ',
    2 => 'パー'
];

/**
 * 勝敗を判定する関数
 * @param int $user_h ユーザーの手 (0, 1, 2)
 * @param int $computer_h コンピュータの手 (0, 1, 2)
 * @return string 勝敗の結果 ('勝ち', '負け', '引き分け')
 */
function judge_janken($user_h, $computer_h) {
    // 引き分け
    if ($user_h === $computer_h) {
        return '引き分け';
    }

    // ユーザーの勝ちパターン: (グー vs チョキ) または (チョキ vs パー) または (パー vs グー)
    // 数値で表すと: (0 vs 1) または (1 vs 2) または (2 vs 0)
    // (user - computer + 3) % 3 の結果が 1 の場合に勝ち
    if (($user_h - $computer_h + 3) % 3 === 1) {
        return '負け'; // 逆の計算になるため、1の場合はコンピュータの勝ち
    }

    // それ以外はユーザーの勝ち
    return '勝ち';
}

// POSTリクエストが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_hand'])) {
    // ユーザーの手を取得
    $user_hand_key = (int)$_POST['user_hand'];

    // ユーザーの手が有効な範囲内か確認
    if (array_key_exists($user_hand_key, $hands)) {
        $user_hand = $hands[$user_hand_key];

        // コンピュータの手をランダムに決定 (0, 1, 2)
        $computer_hand_key = array_rand($hands);
        $computer_hand = $hands[$computer_hand_key];

        // 勝敗判定
        $result = judge_janken($user_hand_key, $computer_hand_key);

        // 結果メッセージの生成
        $message = "あなたは **{$user_hand}**、コンピュータは **{$computer_hand}** でした。<br>";
        $message .= "結果は... **{$result}** です！";

    } else {
        $message = '不正な入力です。もう一度手を選んでください。';
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>PHP じゃんけんゲーム</title>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; margin-top: 50px; }
        .container { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .result-message { margin: 20px 0; padding: 10px; background-color: #f0f0f0; border: 1px solid #ddd; border-radius: 5px; font-size: 1.1em; }
        .hand-option { margin: 5px; }
        button { padding: 10px 20px; font-size: 1.2em; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 5px; }
        button:hover { background-color: #45a049; }
        .hand-label { display: inline-block; width: 60px; text-align: center; }
        .winning { color: green; font-weight: bold; }
        .losing { color: red; font-weight: bold; }
        .draw { color: blue; font-weight: bold; }
        /* 結果に応じて色を付ける */
        <?php if ($result === '勝ち'): ?>
            .result-message { background-color: #e6ffe6; border-color: #4CAF50; }
            .result-message strong:last-child { color: green; }
        <?php elseif ($result === '負け'): ?>
            .result-message { background-color: #ffe6e6; border-color: #f44336; }
            .result-message strong:last-child { color: red; }
        <?php elseif ($result === '引き分け'): ?>
            .result-message { background-color: #e6e6ff; border-color: #2196F3; }
            .result-message strong:last-child { color: blue; }
        <?php endif; ?>
    </style>
</head>
<body>

<div class="container">
    <h1>じゃんけんゲーム</h1>

    <div class="result-message">
        <?php echo $message; ?>
    </div>

    <form method="POST" action="janken.php">
        <div class="hand-option">
            <label class="hand-label">
                <input type="radio" name="user_hand" value="0" required>
                グー
            </label>
        </div>
        <div class="hand-option">
            <label class="hand-label">
                <input type="radio" name="user_hand" value="1" required>
                チョキ
            </label>
        </div>
        <div class="hand-option">
            <label class="hand-label">
                <input type="radio" name="user_hand" value="2" required>
                パー
            </label>
        </div>
        
        <p><button type="submit">勝負！</button></p>
    </form>
</div>

</body>
</html>
