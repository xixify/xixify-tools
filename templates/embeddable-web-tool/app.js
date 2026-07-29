document.addEventListener('DOMContentLoaded', () => {
  const actionBtn = document.getElementById('action-btn');
  const userInput = document.getElementById('user-input');
  const outputSection = document.getElementById('output-section');
  const resultDisplay = document.getElementById('result-display');

  if (actionBtn && userInput) {
    actionBtn.addEventListener('click', () => {
      const val = userInput.value.trim();
      if (!val) {
        alert('Please enter some text!');
        return;
      }

      // Logic goes here
      resultDisplay.textContent = `Processed output length: ${val.length} characters`;
      outputSection.classList.remove('hidden');
    });
  }
});
