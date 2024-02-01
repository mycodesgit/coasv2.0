function updateDateTime() {
    var selectedOption = document.querySelector('[name="dateID"]');
    var selectedDate = selectedOption.options[selectedOption.selectedIndex].text;

    // Split the selected date and time
    var dateTimeArray = selectedDate.split(' ');

    // Format the date as YYYY-MM-DD
    var formattedDate = new Date(dateTimeArray.slice(0, 3).join(' ')).toLocaleDateString('en-CA', { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Manila' }).replace(/(\d+)\/(\d+)\/(\d+)/, '$3-$1-$2');

    // Update the date input
    document.getElementById('selectedDate').value = formattedDate;

    // Extract the time input
    var selectedTime = dateTimeArray.slice(3).join(' ');

    // Format the time as HH:mm:ss without AM/PM
    var formattedTime = new Date('2000-01-01 ' + selectedTime).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Manila' });
    document.getElementById('selectedTime').value = formattedTime;
}