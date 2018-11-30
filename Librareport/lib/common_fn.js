//var a=monthList('2014-01-01', '2018-10-01')
function monthList(startDate, endDate) {
    var start = startDate.split('-');
    var end = endDate.split('-');
    var startYear = parseInt(start[0]);
    var endYear = parseInt(end[0]);
    var dates = [];

    for (var i = startYear; i <= endYear; i++) {
        var endMonth = i != endYear ? 11 : parseInt(end[1]) - 1;
        var startMon = i === startYear ? parseInt(start[1]) - 1 : 0;
        for (var j = startMon; j <= endMonth; j = j > 12 ? j % 12 || 11 : j + 1) {
            var month = j + 1;
            var displayMonth = month < 10 ? '0' + month : month;
            dates.push([i, displayMonth].join('-'));
        }
    }
    return dates;
}

// var getDaysInMonth = daysInMonth(2018, 08);	
function daysInMonth(year, month) {
    return new Date(year, month, 0).getDate();
}
//
function whatDayToday() {
    var d = new Date();
    var n = d.getDay();
    return n;
}
//var date2 = new Date('2018-10-12T00:00:00');
//var n = date2.getDay();
//console.log(n );
//5
function whatDay(date) {

    var n = date.getDay();
    return n;
}

function getCurrentDate()
{
	var d = new Date();
	var day = d.getDate();
	return day;
}