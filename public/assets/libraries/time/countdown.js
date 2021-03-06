var x = document.getElementById('waktu').value;
TargetDate = x;
BackColor = 'white';
ForeColor = "navy";
CountActive = true;
CountStepper = -1;
LeadingZero = true;
DisplayFormat = "%%D%%:%%H%%:%%M%%:%%S%%";
FinishMessage = "It is finally here!";

function calcage(secs, num1, num2) {
    s = ((Math.floor(secs / num1)) % num2).toString();
    if (LeadingZero && s.length < 2)
        s = "0" + s;
    return "<b>" + s + "</b>";
}

function CountBack(secs) {
    if (secs < 0) {
        $('#formUjian').submit();
        document.form('#formUjian').submit();
        window.location.href = "{{ route('soal.selesai', $soal->id) }}";
        return;
    }
    DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs, 86400, 100000));
    DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs, 3600, 24));
    DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs, 60, 60));
    DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs, 1, 60));

    document.getElementById("cntdwn").innerHTML = DisplayStr;
    if (CountActive)
        setTimeout("CountBack(" + (secs + CountStepper) + ")", SetTimeOutPeriod);
}

function putspan(backcolor, forecolor) {
    document.getElementById('setTime').innerHTML = "<span id='cntdwn' style='background-color:" + backcolor +
        "; color:" + forecolor + "'></span>";
}

CountStepper = Math.ceil(CountStepper);
if (CountStepper == 0)
    CountActive = false;
var SetTimeOutPeriod = (Math.abs(CountStepper) - 1) * 1000 + 990;
putspan(BackColor, ForeColor);
var dthen = new Date(TargetDate);
var dnow = new Date();
if (CountStepper > 0)
    ddiff = new Date(dnow - dthen);
else
    ddiff = new Date(dthen - dnow);
gsecs = Math.floor(ddiff.valueOf() / 1000);
CountBack(gsecs);
