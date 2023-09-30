
let TimerOperationTimeAnimateLoading = 600;
let CurrentTimerOperationTimeAnimateLoading = TimerOperationTimeAnimateLoading;
let TimerOperationFlag = false;
let intervalID;
function ShowLoadingAnimation(){
    $('#veilMainDownload').css('display', 'flex');
    intervalID = setInterval(function(){
        TimerOperationFlag = true;
        if(CurrentTimerOperationTimeAnimateLoading > 0){
            CurrentTimerOperationTimeAnimateLoading -= 100;
        }

        if(CurrentTimerOperationTimeAnimateLoading <= 0 && TimerOperationFlag === true){
            clearTimeout(intervalID);
            CurrentTimerOperationTimeAnimateLoading = TimerOperationTimeAnimateLoading;
            TimerOperationFlag = false;
            $('#veilMainDownload').css('display', 'none');
        }
    }, 100);
}

function HideLoadingAnimation(){
    TimerOperationFlag = true;
}

/*console.log(window.location.pathname);
console.log(window.location.href);
console.log(window.location.origin);
console.log(document.referrer);*/

