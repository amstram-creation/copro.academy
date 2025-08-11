// emojis-unicode.js
export const emojiGroups = {
  education: [0x1f393, 0x1f3eb],
  training_actions: [0x1f3c3, 0x1f3cb],
  learning_materials: [0x1f4d5, 0x1f4da],
  documentation: [0x1f4da, 0x1f4dd],
  professions: [0x1f9d1, 0x1f9dd],
  achievements: [0x1f396, 0x1f3c6],
  communication: [0x1f4e0, 0x1f4ef],
  office_tools: [0x1f4c0, 0x1f4cb],
  documents: [0x1f4c3, 0x1f4d6],
  finance: [0x1f4b0, 0x1f4b8],
  analytics: [0x1f4c8, 0x1f4ca],
  logistics: [0x1f690, 0x1f6a2],
  time: [0x23f0, 0x23f3],
  schedule: [0x1f4c5, 0x1f4c6],
  location: [0x1f5fa, 0x1f5ff],
  technology: [0x1f4f1, 0x1f4fa],
  tools: [0x1f527, 0x1f529],
  events: [0x1f3a4, 0x1f3a7],
};

function generateEmojis(groups) {
  const emojis = [];
  for (const name of groups) {
    if (!emojiGroups[name]) continue;
    const [start, end] = emojiGroups[name];
    for (let code = start; code <= end; code++) {
      const emoji = String.fromCodePoint(code);
      if (/\p{Emoji}/u.test(emoji)) {
        emojis.push({ emoji, code });
      }
    }
  }
  return emojis;
}

let modal = null;
let currentTarget = null;

function createModal() {
  if (modal) return modal;

  modal = document.createElement('div');
  modal.className = 'emoji-modal';
  modal.innerHTML = `
    <div class="emoji-backdrop" role="dialog" aria-modal="true" aria-label="Select emoji">
      <div class="emoji-content">
        <header class="emoji-header">
          <button type="button" class="emoji-close" aria-label="Close">&times;</button>
        </header>
        <div class="emoji-grid" role="grid"></div>
      </div>
    </div>
  `;

  document.body.appendChild(modal);
  return modal;
}

function showModal(groups = []) {
  const emojis = generateEmojis(
    groups.length ? groups : Object.keys(emojiGroups)
  );

  const modal = createModal();
  const grid = modal.querySelector('.emoji-grid');

  grid.innerHTML = emojis
    .map(
      ({ emoji, code }) =>
        `<button type="button" class="emoji-btn" data-code="${code}" role="gridcell">${emoji}</button>`
    )
    .join('');

  modal.style.display = 'block';
  grid.firstElementChild?.focus();

  document.addEventListener('keydown', handleModalKeys);
}

function hideModal() {
  if (!modal) return;
  modal.style.display = 'none';
  currentTarget?.focus();
  document.removeEventListener('keydown', handleModalKeys);
}

function insertEmoji(emoji) {
  if (!currentTarget) return;

  const start = currentTarget.selectionStart || 0;
  const end = currentTarget.selectionEnd || 0;
  const value = currentTarget.value || '';

  currentTarget.value = value.slice(0, start) + emoji + value.slice(end);

  const newPos = start + emoji.length;
  currentTarget.setSelectionRange(newPos, newPos);
  currentTarget.focus();

  hideModal();
}

function handleModalKeys(e) {
  if (!modal || modal.style.display === 'none') return;

  const grid = modal.querySelector('.emoji-grid');
  const buttons = [...grid.querySelectorAll('.emoji-btn')];
  const focused = document.activeElement;
  const current = buttons.indexOf(focused);

  switch (e.key) {
    case 'Escape':
      e.preventDefault();
      hideModal();
      break;

    case 'Tab':
      e.preventDefault();
      const next = e.shiftKey
        ? current <= 0
          ? buttons.length - 1
          : current - 1
        : current >= buttons.length - 1
        ? 0
        : current + 1;
      buttons[next]?.focus();
      break;

    case 'ArrowRight':
      e.preventDefault();
      buttons[Math.min(current + 1, buttons.length - 1)]?.focus();
      break;

    case 'ArrowLeft':
      e.preventDefault();
      buttons[Math.max(current - 1, 0)]?.focus();
      break;

    case 'ArrowDown':
      e.preventDefault();
      const cols = Math.floor(
        grid.offsetWidth / (buttons[0]?.offsetWidth || 32)
      );
      buttons[Math.min(current + cols, buttons.length - 1)]?.focus();
      break;

    case 'ArrowUp':
      e.preventDefault();
      const colsUp = Math.floor(
        grid.offsetWidth / (buttons[0]?.offsetWidth || 32)
      );
      buttons[Math.max(current - colsUp, 0)]?.focus();
      break;

    case 'Enter':
    case ' ':
      e.preventDefault();
      if (focused.classList.contains('emoji-btn')) {
        insertEmoji(focused.textContent);
      }
      break;
  }
}

// Track focused input
document.addEventListener('focusin', (e) => {
  if (e.target.matches('input, textarea, [contenteditable]')) {
    currentTarget = e.target;
  }
});

// Handle trigger buttons
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('emoji-trigger')) {
    e.preventDefault();

    const groups =
      e.target.dataset.emoji?.split(',').map((s) => s.trim()) || [];

    console.log('Emoji groups:', groups);
    showModal(groups);
  }

  if (
    e.target.classList.contains('emoji-close') ||
    e.target.classList.contains('emoji-backdrop')
  ) {
    hideModal();
  } else if (e.target.classList.contains('emoji-btn')) {
    insertEmoji(e.target.textContent);
  }
});

export default function hideEmojiModal() {
  return { hideModal };
}
