/**
 * Created by EMF Brian on 12/15/2015.
 */
$(function() {
    //<div id="paymentSettings" data-signup="<?php echo $user->signup_commission; ?>" data-renewal="<?php echo $user->renewal_commission; ?>" data-mbonus="<?php echo $user->monthly_bonus; ?>" data-ybonus="<?php echo $user->yearly_bonus; ?>"></div>

    var OVERALLCOMMISSION = 33;

    var signUpCommission = parseInt($('#paymentSettings').data('signup'));
    var renewal = parseInt($('#paymentSettings').data('renewal'));
    var mbonus = parseInt($('#paymentSettings').data('mbonus'));
    var ybonus = parseInt($('#paymentSettings').data('ybonus'));


    $('.markAsPaid').click(function() {
        var amount = $(this).data('amount');
        calculateCommission();
    });

    $('#addMonthlyBonus').click(function() {
        var total = parseInt($('#amountToPay').data('totals'));
        if(total>0) {
            var newTotal = calculateMonthlyCommission(total);
            $('#amountToPay').html('Total Commission: $' + newTotal.toFixed(2)).attr('data-totals', total.toFixed(2));
        } else {
            $(this).attr('checked', false);
        }
    });

    function calculateMonthlyCommission(total)
    {

    }

    function calculateCommission()
    {
        /*
        Loop through all the pending payments and check if they are checked. If checked determine if the type
        ('renewal payment, 'new payment') of payment and apply the proper percentages and commissions based on the
        payment settings div from above
         */
        var commission = 0;
        var salesVolume = 0;
        $('.markAsPaid').each(function() {
            if($(this).is(':checked')) {
                var amount = $(this).data('amount');
                var type = $(this).data('type');
                var lineCommission = 0;
                salesVolume = salesVolume+amount;
                if(type=='n') {
                    lineCommission = newCommissionAmount(amount);
                } else {
                    lineCommission = renewalCommisssionAmount(amount);
                }
                commission = commission+lineCommission;
            }
        });

        if(commission>0) {
            $('#amountToPay').html('Total Commission: $' + commission.toFixed(2)).attr('data-totals', commission.toFixed(2));
        } else {
            $('#amountToPay').html('').attr('data', '0');
        }
        console.log('%c Total Commission: '+commission.toFixed(2), 'background: #222; color: #bada55');
        commission = 0;
        $('#amountToPay').attr('volume', salesVolume.toFixed(2));
        salesVolume = 0;
    }

    /*
     Returns amount of commission received for a single checked payment based on payment settings div.
     */
    function renewalCommisssionAmount(amount)
    {
        var renewal = (signUpCommission*parseFloat(amount))/100;
        console.log('Renewal Commission is: '+renewal.toFixed(2));
        return renewal;
    }

    /*
        Returns amount of commission received for a single checked payment based on payment settings div.
     */
    function newCommissionAmount(amount)
    {
        var commission = (signUpCommission*parseFloat(amount))/100;
        console.log('Sign Up Commission is: '+commission.toFixed(2));
        return commission;
    }


});