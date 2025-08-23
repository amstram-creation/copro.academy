function formatPubDates(selector = 'time[datetime]') {
    console.log('Formatting date:', selector);

  document.querySelectorAll(selector).forEach((el) => {
    const datetime = (el.getAttribute('datetime') ?? '').trim().replace(' ', 'T');
    const date = new Date(datetime);
    console.log('Formatting date:', datetime, date);
    if (!isNaN(date)) {
      // Always show the date
      let formatted = date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
      });

      // Detect if datetime string includes a time portion
      // (e.g., contains "T" and not just "YYYY-MM-DD")
      const hasTime = /T\d{2}:\d{2}/.test(datetime);

      if (hasTime) {
        formatted += ' à ' + date.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit',
          });
      }

      el.textContent = formatted;
    }
  });
}

export default formatPubDates;
