/**
 * Admin JavaScript Handler for Xixify Partnership Tracker (Senior Upgrade)
 */
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('xixify-status-select');
    const amountInput = document.getElementById('xixify-amount-input');
    const expensesInput = document.getElementById('xixify-expenses-input');
    const paidWrapper = document.getElementById('xixify-paid-wrapper');
    const paidInput = document.getElementById('xixify-paid-input');
    const duePreview = document.getElementById('xixify-due-preview');
    const netPreview = document.getElementById('xixify-net-preview');
    const partnerPreview = document.getElementById('xixify-partner-preview');

    function calculateMath() {
        if (!amountInput) return;

        const amount = parseFloat(amountInput.value) || 0;
        const expenses = parseFloat(expensesInput ? expensesInput.value : 0) || 0;
        const status = statusSelect ? statusSelect.value : 'Pending';

        let paid = parseFloat(paidInput ? paidInput.value : 0) || 0;

        if (status === 'Paid') {
            paid = amount;
            if (paidInput) paidInput.value = amount;
            if (paidWrapper) paidWrapper.style.display = 'none';
        } else if (status === 'Pending') {
            paid = 0;
            if (paidInput) paidInput.value = 0;
            if (paidWrapper) paidWrapper.style.display = 'none';
        } else if (status === 'Partial') {
            if (paidWrapper) paidWrapper.style.display = 'block';
        }

        const due = Math.max(0, amount - paid);
        const net = amount - expenses;
        const partnerEach = Math.round(net / 2);

        if (duePreview) duePreview.textContent = '৳ ' + due.toLocaleString();
        if (netPreview) netPreview.textContent = '৳ ' + net.toLocaleString();
        if (partnerPreview) partnerPreview.textContent = '৳ ' + partnerEach.toLocaleString() + ' / partner';
    }

    if (statusSelect) statusSelect.addEventListener('change', calculateMath);
    if (amountInput) amountInput.addEventListener('input', calculateMath);
    if (expensesInput) expensesInput.addEventListener('input', calculateMath);
    if (paidInput) paidInput.addEventListener('input', calculateMath);

    calculateMath();
});
