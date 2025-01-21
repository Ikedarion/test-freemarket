<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>決済完了のお知らせ</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .mail__content {
            width: 95%;
            max-width: 600px;
            margin: 5% auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .mail__group {
            margin-bottom: 20px;
        }

        .mail__group h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333333;
        }

        .mail__group p {
            font-size: 16px;
            color: #555555;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        th {
            font-weight: bold;
            color: #333333;
        }

        td {
            color: #666666;
        }

        tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        @media (max-width: 480px) {
            .mail__content {
                padding: 15px;
            }

            .mail__group h3 {
                font-size: 18px;
            }

            .mail__group p {
                font-size: 14px;
            }

            th,
            td {
                font-size: 13px;
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="mail__content">
        <div class="mail__group">
            <h3>ご購入ありがとうございます。</h3>
            <p>以下の内容で決済が正常に完了いたしましたので、お知らせいたします。</p>
        </div>
        <table class="detail__group">
            <tr class="product-detail">
                <th>商品名:</th>
                <td>{{ $purchase->product->name }}</td>
            </tr>
            <tr class="product-detail">
                <th>価格:</th>
                <td>{{ number_format($purchase->price) }}円</td>
            </tr>
            <tr class="product-detail">
                <th>決済日:</th>
                <td>{{ $completedAt->format('Y/m/d H:i') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>