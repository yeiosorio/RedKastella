<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php
	echo "
	<div style='text-align: center'>
		<h2 style='color: #29a9de; font-size: 41px;'>$buyerName el estado de su transaccíon ha sido <b>¡$labelStatePayment!</b> </h2>
	</div><br>
	<div style='width: 82%; margin: auto;'>
		<div style='background-color: #f5f5f5; border-radius: 5px 5px 5px 5px; padding: 24px;'>
			<p>$buyerName</p>
			<p> Usted ha adquirido el plan premium de Red Kastella: <b>$valor</b> </p>
			<p>Si no reconoce esta cuenta o no es su identificación, por favor comuníquese con Augusta consultores.<br>
				Sede Norte 1: Car 15 # 1N – 49. - WhatsApp 310-8469345
				Bogota, Colombia
				PBX: (6) 7455566

				Visítanos en:
				www.redKastella.com -
				https://www.facebook.com/FundacionAlejandroLondono.Oficial
			</p>
		</div>
	</div>
	</div>";
?>
