<?php
/**
 * Plugin Name: Case Assist Personal Injury Calculator
 * Description: A tool designed to assist in calculating a fair settlement payment estimate. For various personal injury cases including; car accidents, premises liability, and more.
 * Version: 1.0.0
 * Author: Case Assist
 * Author URI: https://caseassist.co/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Enqueue the plugin stylesheet
function ca_personal_injury_calculator_enqueue_styles() {
    wp_enqueue_style('ca-personal-injury-calculator-style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'ca_personal_injury_calculator_enqueue_styles');

// Add a shortcode for the form
function ca_personal_injury_calculator_shortcode() {
    ob_start();
    ?>
    <style>
		.mpic-main{
			border: 2px solid #f0f0f0;
			padding: 0 15px;
			border-radius: 25px;
		}
        .personal-injury-calculator-table {
            width: 100%;
            border-collapse: collapse;
            /*border-radius: 10px;
            border: 1px solid #ddd;*/
            overflow: hidden;
        }

        .personal-injury-calculator-table th,
        .personal-injury-calculator-table td {
			border:none;
            padding: 15px;
            text-align: left;
            vertical-align: middle;
        }

        .personal-injury-calculator-table th {
            background-color: #f0f0f0;
            text-align: center;
            position: center; /* Add this line */
        }


        .personal-injury-calculator-table input[type="text"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .personal-injury-calculator-table .calculate-button-cell {
            text-align: center;
            vertical-align: middle;
        }

        .personal-injury-calculator-table .result-cell {
            text-align: center;
            vertical-align: middle;
        }

        .info-butt {
            display: inline-block;
            width: 18px;
            height: 18px;
            text-align: center;
            border-radius: 50%;
            cursor: pointer;
            line-height: 18px;
			color: #fff;
			padding: 3px;
			font-size: 14px;
			background-color: #000;
        }

        .info-tooltip {
        display: none;
        position: absolute;
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        padding: 10px;
        font-size: 12px;
        border-radius: 4px;
        z-index: 9999;
        /* Adjust the positioning as needed */
        top: -30px;
        left: 0;
}


        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input[type="checkbox"] {
            display: none;
        }

        .toggle-switch-label {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;
            background-color: #ccc;
            border-radius: 12px;
        }

        .toggle-switch-label:before {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background-color: #fff;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .toggle-switch input[type="checkbox"]:checked + .toggle-switch-label:before {
            transform: translateX(26px);
        }
		.label-toggle-switch{
			display: inline-block;
			height: 24px;
			vertical-align: super;
		}
    </style>
	<div class="mpic-main">
        <h2 style="text-align: center;">Personal Injury Calculator</h2>
        <p style="text-align: center;">This calculator is a tool to assist with estimating your potential personal injury reimbursement. It is no substitute for legal advice but can provide a general idea of what compensation you may be able to receive.</p>
        <p style="text-align:center; color:red">Numbers only. No commas, dollar signs ($), etc.</p>
    
        <table class="personal-injury-calculator-table">
            <tr>
                <th style="vertical-align: middle;    border-top-left-radius: 10px;border-top-right-radius: 10px;">
                    <span>Medical Fees:</span>
                    <div class="info-butt" id="medical-fees-tooltip">i</div>
                    <div class="info-tooltip" id="medical-fees-tooltip-content">The overall costs of medical bills. Paid for by insurance or personal finances.</div>
                </th>
                <td style="vertical-align: middle;"><input type="text" id="total_medical_expenses" name="total_medical_expenses" value="0" required></td>
            </tr>
            <tr>
                <th style="vertical-align: middle;">
                    <span>Future Medical Fees:</span>
                    <div class="info-butt" id="future-medical-fees-tooltip">i</div>
                    <div class="info-tooltip" id="future-medical-fees-tooltip-content">If you foresee medical assistance needed in the future, what do you estimate these fees will cost?</div>
                </th>
                <td style="vertical-align: middle;"><input type="text" id="future_medical_expenses" name="future_medical_expenses" value="0" required></td>
            </tr>
            <tr>
                <th style="vertical-align: middle;">
                    <span>Property / Vehicle Damage:</span>
                    <div class="info-butt" id="vehicle-damage-tooltip">i</div>
                    <div class="info-tooltip" id="vehicle-damage-tooltip-content">The fees required to replace or repair damage to a car, truck, motorcycle, etc. Insurance reimbursement amount.</div>
                </th>
                <td style="vertical-align: middle;"><input type="text" id="vehicle_damage" name="vehicle_damage" value="0" required></td>
            </tr>
            <tr>
                <th style="vertical-align: middle;">
                    <span>Income Loss:</span>
                    <div class="info-butt" id="income-loss-tooltip">i</div>
                    <div class="info-tooltip" id="income-loss-tooltip-content">The overall amount of income lost as a result of missed work due to injuries.</div>
                </th>
                <td style="vertical-align: middle;"><input type="text" id="lost_income" name="lost_income" value="0" required></td>
            </tr>
            <tr>
                <th style="vertical-align: middle;">
                    <span>Future Income Loss:</span>
                    <div class="info-butt" id="future-income-loss-tooltip">i</div>
                    <div class="info-tooltip" id="future-income-loss-tooltip-content">The estimated amount of income you will lose due to missing work as a result of your injuries.</div>
                </th>
                <td style="vertical-align: middle;"><input type="text" id="future_lost_income" name="future_lost_income" value="0" required></td>
            </tr>
            <tr>
                <th style="vertical-align: middle;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                    <span>Services Rendered:</span>
                    <div class="info-butt" id="general-damages-tooltip">i</div>
                    <div class="info-tooltip" id="general-damages-tooltip-content">Have you experienced or been through any of the following treatments/services?</div>
                </th>
                <td style="vertical-align: middle;">
                	<label for="occupational_therapy" class="toggle-switch">
                        <input type="checkbox" id="occupational_therapy" name="general_damages[]" value="occupational_therapy">
                        <span class="toggle-switch-label"></span>
                    </label>
                    <label class="label-toggle-switch">Occupational Therapy</label>
                    <br>
                    <label for="chiropractic_treatment" class="toggle-switch">
                        <input type="checkbox" id="chiropractic_treatment" name="general_damages[]" value="chiropractic_treatment">
                        <span class="toggle-switch-label"></span>
                    </label>
                    <label class="label-toggle-switch">Chiropractor Therapy</label>
                    <br>
                    <label for="broken_bones" class="toggle-switch">
                        <input type="checkbox" id="broken_bones" name="general_damages[]" value="broken_bones">
                        <span class="toggle-switch-label"></span>
                    </label>
                    <label class="label-toggle-switch">Breaks/Fractures</label>
                    <br>
                    <label for="surgery" class="toggle-switch">
                        <input type="checkbox" id="surgery" name="general_damages[]" value="surgery">
                        <span class="toggle-switch-label"></span>
                    </label>
                    <label class="label-toggle-switch">Surgery</label>
                </td>
            </tr>
            <tr>
                <td class="calculate-button-cell" colspan="2" style="text-align: center; vertical-align: middle;">
                    <br>
                    <input type="button" value="Calculate" onclick="calculateEstimatedSettlement()">
                    <br>
                    <div id="estimated_settlement_result"></div>
                </td>
            </tr>
            <tr>
                <td class="result-cell" colspan="2">
                    <h3 style="text-align: center; margin: 0;">Estimated Settlement</h3>
                    <h2 style="text-align: center; font-weight: bold;">
                        $<span id="settlement_base"></span> - $<span id="settlement_top"></span>
                    </h2>
                    <p id="settlement_prompt" style="text-align: center; color: red; font-weight: bold; display: none;"></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <small>
                        <i>Disclaimer: Our calculator provides an estimate of your potential compensation. For personalized guidance and legal support, reach out to our team today. Remember, while the calculator is helpful, it&#39;s no substitute for expert advice. Claim values are influenced by unique factors. Contact us to help you navigate this process with care and understanding.</i>
                    </small>
                </td>
            </tr>
        </table>
	</div>
    <script>
        // Calculate the estimated settlement and display the result
        function calculateEstimatedSettlement() {
            const totalmedExp = parseFloat(document.getElementById('total_medical_expenses').value);
            const vehicleDamage = parseFloat(document.getElementById('vehicle_damage').value);
            const lostIncome = parseFloat(document.getElementById('lost_income').value);
            const fuXinc = parseFloat(document.getElementById('future_lost_income').value);
            const futuremedExp = parseFloat(document.getElementById('future_medical_expenses').value);
            const generalDamages = Array.from(document.querySelectorAll('input[name="general_damages[]"]:checked')).map(checkbox => checkbox.value);

            let baseNumber = 0;
            let topNumber = 0;

            if (isNaN(totalmedExp) || isNaN(vehicleDamage) || isNaN(lostIncome) || isNaN(fuXinc) || isNaN(futuremedExp)) {
                document.getElementById('settlement_prompt').textContent = "Please enter a numerical value to correctly calculate your results.";
                document.getElementById('settlement_prompt').style.display = "block";
                document.getElementById('settlement_base').textContent = "";
                document.getElementById('settlement_top').textContent = "";
            } else {
                document.getElementById('settlement_prompt').style.display = "none";

                if (generalDamages.length === 0) {
                    // When no checkboxes are selected
                    baseNumber = totalmedExp * 1 + futuremedExp * 1 + vehicleDamage + lostIncome + fuXinc;
                    topNumber = (totalmedExp * 1 + futuremedExp * 1) * 3 + vehicleDamage + lostIncome + fuXinc;
                } else {
                    // Applying the formulas based on the selected checkboxes
                    if (generalDamages.includes('broken_bones') && generalDamages.includes('surgery') && generalDamages.includes('chiropractic_treatment') && generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp * 4 + futuremedExp * 4 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 4 + futuremedExp * 4) * 1.67 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones') && generalDamages.includes('surgery') && generalDamages.includes('chiropractic_treatment')) {
                        baseNumber = totalmedExp * 3 + futuremedExp * 3 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 3 + futuremedExp * 3) * 1.67 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('surgery') && generalDamages.includes('chiropractic_treatment') && generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp * 3.5 + futuremedExp * 3.5 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 3.5 + futuremedExp * 3.5) * 1.67 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones') && generalDamages.includes('occupational_therapy') && generalDamages.includes('chiropractic_treatment')) {
                        baseNumber = totalmedExp * 2 + futuremedExp * 2 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2 + futuremedExp * 2) * 2 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('surgery') && generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp * 2.5 + futuremedExp * 2.5 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2.5 + futuremedExp * 2.5) * 1.8 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones') && generalDamages.includes('surgery')) {
                        baseNumber = totalmedExp * 3 + futuremedExp * 3 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 3 + futuremedExp * 3) * 1.67 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones') && generalDamages.includes('chiropractic_treatment')) {
                        baseNumber = totalmedExp * 2 + futuremedExp * 2 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2 + futuremedExp * 2) * 2 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones') && generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp * 2 + futuremedExp * 2 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2 + futuremedExp * 2) * 2 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('surgery') && generalDamages.includes('broken_bones') && generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp * 3.5 + futuremedExp * 3.5 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 3.5 + futuremedExp * 3.5) * 1.67 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('surgery') && generalDamages.includes('chiropractic_treatment')) {
                        baseNumber = totalmedExp * 2.5 + futuremedExp * 2.5 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2.5 + futuremedExp * 2.5) * 1.8 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones') && generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp * 2 + futuremedExp * 2 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2 + futuremedExp * 2) * 2 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('broken_bones')) {
                        baseNumber = totalmedExp * 2 + futuremedExp * 2 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2 + futuremedExp * 2) * 2 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('surgery')) {
                        baseNumber = totalmedExp * 2.5 + futuremedExp * 2.5 + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp * 2.5 + futuremedExp * 2.5) * 1.8 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('chiropractic_treatment')) {
                        baseNumber = totalmedExp + futuremedExp + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp + futuremedExp) * 2.5 + vehicleDamage + lostIncome + fuXinc;
                    } else if (generalDamages.includes('occupational_therapy')) {
                        baseNumber = totalmedExp + futuremedExp + vehicleDamage + lostIncome + fuXinc;
                        topNumber = (totalmedExp + futuremedExp) * 2.5 + vehicleDamage + lostIncome + fuXinc;
                    }
                }

                document.getElementById('settlement_base').textContent = numberWithCommas(baseNumber.toFixed(0));
                document.getElementById('settlement_top').textContent = numberWithCommas(topNumber.toFixed(0));
            }
        }

        // Add commas to numbers
        function numberWithCommas(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Show and hide the informational tooltips
        const tooltips = [
            { buttonId: 'medical-fees-tooltip', tooltipId: 'medical-fees-tooltip-content' },
            { buttonId: 'vehicle-damage-tooltip', tooltipId: 'vehicle-damage-tooltip-content' },
            { buttonId: 'income-loss-tooltip', tooltipId: 'income-loss-tooltip-content' },
            { buttonId: 'future-income-loss-tooltip', tooltipId: 'future-income-loss-tooltip-content' },
            { buttonId: 'future-medical-fees-tooltip', tooltipId: 'future-medical-fees-tooltip-content' },
            { buttonId: 'general-damages-tooltip', tooltipId: 'general-damages-tooltip-content' },
        ];
        
        // Function to show and position the tooltip next to the cursor
        function showTooltipAtCursor(event, tooltipContent) {
            tooltipContent.style.display = 'block';
            tooltipContent.style.top = event.clientY + 10 + 'px'; // Add an offset to avoid overlapping with the cursor
            tooltipContent.style.left = event.clientX + 10 + 'px'; // Add an offset to avoid overlapping with the cursor
        }

        // Function to hide the tooltip
        function hideTooltip(tooltipContent) {
            tooltipContent.style.display = 'none';
        }
        
// Add event listeners to show and hide the tooltips at cursor position
        tooltips.forEach((tooltip) => {
            const button = document.getElementById(tooltip.buttonId);
            const tooltipContent = document.getElementById(tooltip.tooltipId);

            button.addEventListener('mouseover', (event) => {
                showTooltipAtCursor(event, tooltipContent);
            });

            button.addEventListener('mousemove', (event) => {
                showTooltipAtCursor(event, tooltipContent);
            });

            button.addEventListener('mouseout', () => {
                    hideTooltip(tooltipContent);
        });
    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('ca_personal_injury_calculator', 'ca_personal_injury_calculator_shortcode');
