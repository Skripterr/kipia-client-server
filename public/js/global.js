function showError(message) {
    if (message.length > 0) {
        iziToast.error({
            message: message,
            close: false,
            icon: '',
            position: 'topRight',
            closeOnClick: true,
            animateInside: false,
            timeout: 3000,
            displayMode: 'replace'
        });
    }
}

function showSuccess(message) {
    if (message.length > 0) {
        iziToast.success({
            message: message,
            close: false,
            icon: '',
            position: 'topRight',
            closeOnClick: true,
            animateInside: false,
            timeout: 3000,
            displayMode: 'replace'
        });
    }
}

function showInfo(message) {
    if (message.length > 0) {
        iziToast.info({
            message: message,
            close: false,
            icon: '',
            position: 'topRight',
            closeOnClick: true,
            animateInside: false,
            timeout: 3000,
            displayMode: 'replace'
        });
    }
}

function clickToCopy(text) {
    try {
        var textarea = document.createElement("textarea");
        document.body.appendChild(textarea);
        textarea.value = text;
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
        showInfo("Скопировано.");
    } catch (e) {
        console.log(e)
        showError("Не удалось скопировать текст.");
    }
}

function saveAs(filename, text) {
    try {
        let element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    } catch (e) {
        console.log(e)
        showError("Не удалось отдать файл.");
    } finally {
        showInfo("Файл отдан на скачивание.");
    }
}

function getStartOfDayTimestamp() {
    const currentDate = new Date();
    const startOfDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
    return Math.floor(startOfDay.getTime() / 1000);
}

function getStartOfWeekTimestamp() {
    const date = new Date();
    date.setHours(0, 0, 0, 0);
    let day = date.getDay(),
        diff = date.getDate() - day + (day == 0 ? -6 : 1);
    return Math.floor(new Date(date.setDate(diff)).getTime() / 1000);
}

function getStartOfMonthTimestamp() {
    const currentDate = new Date();
    const startOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    return Math.floor(startOfMonth.getTime() / 1000);
}

function convertTimestampToElapsedTime(timestamp) {
    const currentTime = Math.floor(Date.now() / 1000);
    const elapsedTime = currentTime - timestamp;

    if (elapsedTime <= 60) {
        return 'только что';
    }

    const days = Math.floor(elapsedTime / (24 * 60 * 60));
    const hours = Math.floor((elapsedTime % (24 * 60 * 60)) / (60 * 60));
    const minutes = Math.floor((elapsedTime % (60 * 60)) / 60);
    const seconds = elapsedTime % 60;

    if (days > 0) {
        elapsedTimeString = `${days} д. `;
    } else if (hours > 0) {
        elapsedTimeString = `${hours} ч. `;
    } else if (minutes > 0) {
        elapsedTimeString = `${minutes} мин. `;
    }

    return elapsedTimeString + ' назад';
}

function groupTimestampsByTime(timestamps) {
    const hourCounts = Array(24).fill(0);
    const dayOfWeekCounts = Array(7).fill(0);
    const dayOfMonthCounts = Array(31).fill(0);

    timestamps.forEach(timestamp => {
        const date = new Date(timestamp * 1000);
        const hour = date.getHours();
        const dayOfWeek = (date.getDay() - 1 === -1 ? 6 : date.getDay() - 1);
        const dayOfMonth = date.getDate();

        hourCounts[hour]++;
        dayOfWeekCounts[dayOfWeek]++;
        dayOfMonthCounts[dayOfMonth - 1]++;
    });

    return [
        hourCounts,
        dayOfWeekCounts,
        dayOfMonthCounts
    ];
}

function returnHours() {
    return Array.from({
        length: 24
    }, (_, index) => {
        const date = new Date();
        date.setHours(index);
        return date.toLocaleTimeString('ru-RU', {
            hour: '2-digit',
        }) + ':00';
    });
}

function returnWeekDays() {
    const weekdays = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
    return weekdays;
}

function returnDaysOfMonth() {
    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const days = Array.from({
        length: daysInMonth
    }, (_, index) => index + 1);
    return days;
}

function renderChart(chart, value) {
    switch (value) {
        case '1':
            Application.itemsGet(getStartOfDayTimestamp()).success((response) => {
                if (response.error) {
                    showError(response.message);
                } else {
                    addData(chart, returnHours(), groupTimestampsByTime(response.data.map(obj => obj.timestamp))[0]);
                }
            });
            break;
        case '2':
            Application.itemsGet(getStartOfWeekTimestamp()).success((response) => {
                if (response.error) {
                    showError(response.message);
                } else {
                    addData(chart, returnWeekDays(), groupTimestampsByTime(response.data.map(obj => obj.timestamp))[1]);
                }
            });
            break;
        case '3':
            Application.itemsGet(getStartOfMonthTimestamp()).success((response) => {
                if (response.error) {
                    showError(response.message);
                } else {
                    addData(chart, returnDaysOfMonth(), groupTimestampsByTime(response.data.map(obj => obj.timestamp))[2]);
                }
            });
            break;
    }
}

function parseUrl(url) {
    console.log(url);
    try {
        const parseUrl = new URL(url);
        if (parseUrl.pathname.split('/').length > 0) {
            return parseUrl.pathname.split('/')[parseUrl.pathname.split('/').length - 1]
        }
    }
    catch(e) {
        return null;
    }
}

function stopLoad() {
    $(window).bind('beforeunload', function(e) { 
        return "Unloading this page may lose data. What do you want to do..."
        e.preventDefault();
    });
}