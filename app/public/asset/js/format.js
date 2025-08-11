function formatPubDates(selector = 'time[datetime]') {
  document.querySelectorAll(selector).forEach((el) => {
    const date = new Date(el.getAttribute('datetime'));
    if (!isNaN(date)) {
      el.textContent =
        date.toLocaleDateString('fr-FR', {
          day: '2-digit',
          month: 'long',
          year: 'numeric',
        }) +
        ' à ' +
        date.toLocaleTimeString('fr-FR', {
          hour: '2-digit',
          minute: '2-digit',
        });
    }
  });
}
export default formatPubDates;