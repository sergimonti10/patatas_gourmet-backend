<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .header .company-info {
            text-align: right;
        }
        .invoice-box {
            width: 100%;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .invoice-table th {
            background-color: #f2f2f2;
        }
        .terms {
            margin-top: 20px;
            font-style: italic;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
    <title>Invoice</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <table style="width: 100%; font-size: 12px;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        {{ $order->user->name }} {{ $order->user->surname }}<br>
                        {{ $order->user->email }}<br>
                        {{ $order->user->phone }}<br>
                        {{ $order->user->street }}@if($order->user->number), {{ $order->user->number }} @endif<br>
                        @if($order->user->floor) - {{ $order->user->floor }}, {{ $order->user->staircase }}<br> @endif
                        {{ $order->user->locality }}<br>
                        {{ $order->user->province }}<br>
                        {{ $order->user->postal_code }}
                    </td>
                    <td style="width: 40%; vertical-align: top; text-align: right;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; margin-bottom: 12px">
                                        <img src="../public/logo.png" style="width: 130px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    Patatas Gourmet SL<br>
                                    C/ Comercio, 12 - P.I. Benamejí<br>
                                    Benamejí, Córdoba (14910)<br>
                                    123 456 789<br>
                                    patatasgourmet@gmail.com<br>
                                    A12345678
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-box">
            <table class="invoice-table">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
                @foreach ($order->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ $product->pivot->unit_price }} €</td>
                        <td>{{ $product->pivot->quantity * $product->pivot->unit_price }} €</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Subtotal</strong></td>
                    <td>{{ $order->total_price }} €</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>IVA (21%)</strong></td>
                    <td>{{ number_format(ceil($order->total_price * 0.21 * 100) / 100, 2) }} €</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                    <td>{{ number_format(ceil($order->total_price * 1.21 * 100) / 100, 2) }} €</td>
                </tr>
            </table>
            <div class="terms">
                <strong>Términos legales:</strong><br>
                De conformidad con el Reglamento UE 2016/679 relativo a la Protección de las personas físicas en lo que respecta al Tratamiento de Datos Personales y con la L.O 3/2018 de Protección de Datos Personales y Garantía de Derechos Digitales; le informamos que los datos utilizados en esta presente factura están incluidos en un fichero cuyo titular es PATATAS GOURMET SL; con la finalidad de llevar a cabo la gestión fiscal y contable de la empresa.<br>
                La causa legítima de este tratamiento de datos es el consentimiento. Podrán ser transmitidos al servicio de asesoramiento laboral y fiscal. Se conservarán los años necesarios para cumplir con las obligaciones legales. Sin perjuicio de ello usted podrá ejercitar los derechos de acceso, rectificación, supresión, limitación, portabilidad y oposición, enviando una solicitud con su DNI a C/Comercio, 12 Benamejí (CÓRDOBA) CP 14910 o al e-mail: patatasgourmet@gmail.com<br>
                Registro Mercantil de Córdoba, Tomo 2891, Folio 191, Hoja CO-44529, Inscripción 1
            </div>
        </div>
    </div>
</body>
</html>
