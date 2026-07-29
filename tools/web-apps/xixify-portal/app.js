/**
 * Xixify Partnership Financial & Client Portal Engine
 */

class XixifyPortalApp {
  constructor() {
    this.storageKey = 'xixify_portal_projects';
    this.viewMode = 'admin'; // 'admin' or 'client'
    this.currentFilter = 'all';
    this.searchQuery = '';
    this.projects = this.loadProjects();

    this.initElements();
    this.bindEvents();
    this.render();
  }

  loadProjects() {
    const saved = localStorage.getItem(this.storageKey);
    if (saved) {
      try { return JSON.parse(saved); } catch (e) { console.error(e); }
    }
    return INITIAL_DATA.projects;
  }

  saveProjects() {
    localStorage.setItem(this.storageKey, JSON.stringify(this.projects));
  }

  initElements() {
    this.elGrossRevenue = document.getElementById('metric-gross');
    this.elExpenses = document.getElementById('metric-expenses');
    this.elNetProfit = document.getElementById('metric-net');
    this.elSumayahShare = document.getElementById('metric-sumayah');
    this.elFirozShare = document.getElementById('metric-firoz');
    this.elTotalDues = document.getElementById('metric-dues');

    this.elTableBody = document.getElementById('projects-table-body');
    this.elSearchInput = document.getElementById('search-input');
    this.elFilterBtns = document.querySelectorAll('.filter-btn');
    this.elModeToggleBtn = document.getElementById('mode-toggle-btn');
    this.elAddProjectBtn = document.getElementById('add-project-btn');

    this.elModal = document.getElementById('project-modal');
    this.elModalClose = document.getElementById('modal-close');
    this.elModalCancel = document.getElementById('modal-cancel');
    this.elProjectForm = document.getElementById('project-form');
  }

  bindEvents() {
    // Search input
    if (this.elSearchInput) {
      this.elSearchInput.addEventListener('input', (e) => {
        this.searchQuery = e.target.value.toLowerCase();
        this.renderTable();
      });
    }

    // Filters
    this.elFilterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        this.elFilterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        this.currentFilter = btn.dataset.filter;
        this.renderTable();
      });
    });

    // View mode toggle
    if (this.elModeToggleBtn) {
      this.elModeToggleBtn.addEventListener('click', () => {
        this.viewMode = this.viewMode === 'admin' ? 'client' : 'admin';
        document.body.classList.toggle('client-mode', this.viewMode === 'client');
        this.elModeToggleBtn.textContent = this.viewMode === 'admin' ? '👁️ View as Client' : '🔒 Switch to Admin';
        this.render();
      });
    }

    // Modal triggers
    if (this.elAddProjectBtn) {
      this.elAddProjectBtn.addEventListener('click', () => this.openModal());
    }
    if (this.elModalClose) this.elModalClose.addEventListener('click', () => this.closeModal());
    if (this.elModalCancel) this.elModalCancel.addEventListener('click', () => this.closeModal());

    // Project Form submission
    if (this.elProjectForm) {
      this.elProjectForm.addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleFormSubmit();
      });
    }
  }

  calculateTotals() {
    let gross = 0;
    let expenses = 0;
    let paid = 0;
    let dues = 0;

    this.projects.forEach(p => {
      gross += Number(p.amount || 0);
      expenses += Number(p.expenses || 0);
      paid += Number(p.paid || 0);
      dues += Number(p.due || 0);
    });

    const net = gross - expenses;
    const splitEach = Math.round(net / 2);

    return { gross, expenses, net, splitEach, paid, dues };
  }

  renderMetrics() {
    const totals = this.calculateTotals();
    if (this.elGrossRevenue) this.elGrossRevenue.textContent = `৳ ${totals.gross.toLocaleString()}`;
    if (this.elExpenses) this.elExpenses.textContent = `৳ ${totals.expenses.toLocaleString()}`;
    if (this.elNetProfit) this.elNetProfit.textContent = `৳ ${totals.net.toLocaleString()}`;
    if (this.elSumayahShare) this.elSumayahShare.textContent = `৳ ${totals.splitEach.toLocaleString()}`;
    if (this.elFirozShare) this.elFirozShare.textContent = `৳ ${totals.splitEach.toLocaleString()}`;
    if (this.elTotalDues) this.elTotalDues.textContent = `৳ ${totals.dues.toLocaleString()}`;
  }

  getFilteredProjects() {
    return this.projects.filter(p => {
      if (this.viewMode === 'client' && p.clientVisible === false) return false;
      const matchesSearch = p.name.toLowerCase().includes(this.searchQuery) ||
                            p.client.toLowerCase().includes(this.searchQuery) ||
                            (p.source && p.source.toLowerCase().includes(this.searchQuery));

      if (!matchesSearch) return false;
      if (this.currentFilter === 'all') return true;
      if (this.currentFilter === 'paid') return p.status.toLowerCase() === 'paid';
      if (this.currentFilter === 'partial') return p.status.toLowerCase() === 'partial';
      if (this.currentFilter === 'pending') return p.status.toLowerCase() === 'pending';
      return true;
    });
  }

  renderTable() {
    if (!this.elTableBody) return;
    const list = this.getFilteredProjects();
    this.elTableBody.innerHTML = '';

    if (list.length === 0) {
      this.elTableBody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:#9CA3AF; padding:30px;">No projects found matching criteria.</td></tr>`;
      return;
    }

    list.forEach(p => {
      const net = (p.amount || 0) - (p.expenses || 0);
      const partnerEach = Math.round(net / 2);
      const statusClass = p.status.toLowerCase() === 'paid' ? 'status-paid' :
                          p.status.toLowerCase() === 'partial' ? 'status-partial' : 'status-pending';

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${this.escapeHtml(p.name)}</strong></td>
        <td>${this.escapeHtml(p.client)}</td>
        <td>৳ ${(p.amount || 0).toLocaleString()}</td>
        <td class="admin-only">৳ ${(p.expenses || 0).toLocaleString()}</td>
        <td class="admin-only"><strong>৳ ${net.toLocaleString()}</strong></td>
        <td class="admin-only" style="font-size:12px; color:#A5B4FC;">৳ ${partnerEach.toLocaleString()} / ea</td>
        <td><span class="status-tag ${statusClass}">${p.status}</span></td>
        <td><span style="color:${p.due > 0 ? '#F87171' : '#34D399'}">৳ ${(p.due || 0).toLocaleString()}</span></td>
        <td class="admin-only">
          <button class="action-btn-sm" onclick="portalApp.markPaid('${p.id}')">✓ Mark Paid</button>
          <button class="action-btn-sm" onclick="portalApp.deleteProject('${p.id}')">🗑️</button>
        </td>
      `;
      this.elTableBody.appendChild(tr);
    });
  }

  openModal() {
    if (this.elModal) this.elModal.classList.remove('hidden');
  }

  closeModal() {
    if (this.elModal) this.elModal.classList.add('hidden');
    if (this.elProjectForm) this.elProjectForm.reset();
  }

  handleFormSubmit() {
    const name = document.getElementById('proj-name').value;
    const client = document.getElementById('proj-client').value;
    const amount = Number(document.getElementById('proj-amount').value) || 0;
    const expenses = Number(document.getElementById('proj-expenses').value) || 0;
    const paid = Number(document.getElementById('proj-paid').value) || 0;
    const month = document.getElementById('proj-month').value;
    const status = document.getElementById('proj-status').value;

    const due = Math.max(0, amount - paid);

    const newProject = {
      id: 'proj-' + Date.now(),
      name,
      client,
      source: client,
      leadOwner: 'Sumayah',
      amount,
      expenses,
      paid,
      due,
      month,
      status,
      distributed: 'No',
      clientVisible: true,
      tasks: []
    };

    this.projects.unshift(newProject);
    this.saveProjects();
    this.closeModal();
    this.render();
  }

  markPaid(id) {
    const proj = this.projects.find(p => p.id === id);
    if (proj) {
      proj.paid = proj.amount;
      proj.due = 0;
      proj.status = 'Paid';
      this.saveProjects();
      this.render();
    }
  }

  deleteProject(id) {
    if (confirm('Are you sure you want to delete this project entry?')) {
      this.projects = this.projects.filter(p => p.id !== id);
      this.saveProjects();
      this.render();
    }
  }

  escapeHtml(str) {
    return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  render() {
    this.renderMetrics();
    this.renderTable();
  }
}

let portalApp;
document.addEventListener('DOMContentLoaded', () => {
  portalApp = new XixifyPortalApp();
});
