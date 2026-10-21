// Global function to set scope
let currentScope = localStorage.getItem('dashboard_scope') || 'global';
let refreshAllData;

function setScope(scope) {
    currentScope = scope;
    localStorage.setItem('dashboard_scope', scope);

    // Update UI
    updateScopeUI(scope);

    // Refresh Data
    if (typeof refreshAllData === 'function') {
        refreshAllData();
    }
}

function updateScopeUI(scope) {
    if (scope === 'global') {
        $('#scope-global').removeClass('btn-outline-primary').addClass('btn-primary');
        $('#scope-personal').removeClass('btn-primary').addClass('btn-outline-primary');
    } else {
        $('#scope-personal').removeClass('btn-outline-primary').addClass('btn-primary');
        $('#scope-global').removeClass('btn-primary').addClass('btn-outline-primary');
    }
}

$(document).ready(function () {
    // Apply initial UI state based on currentScope (which may have been loaded from localStorage)
    updateScopeUI(currentScope);

    // isAdmin is defined globally in the Blade file before loading this script
    const userRoleAdmin = typeof isAdmin !== 'undefined' ? isAdmin : false;

    // Unified data loader
    function loadDashboardSummary() {
        $.ajax({
            url: "/api/dashboard/summary",
            type: "GET",
            data: { scope: currentScope },
            dataType: "json",
            cache: false,
            success: function (res) {
                if (res.success) {
                    const data = res.data;
                    
                    // Update Stats
                    animateValue('total-tugas', data.stats.total_tugas);
                    animateValue('total-users', data.stats.total_users);
                    animateValue('total-selesai', data.stats.total_selesai);
                    animateValue('total-pending', data.stats.total_pending);
                    updateCompletionRate(data.stats);

                    // Update Recent Tasks
                    renderRecentTasks(data.recent_tasks);

                    // Update Trends
                    if (trendChart) {
                        trendChart.data.labels = data.trend.labels;
                        trendChart.data.datasets[0].data = data.trend.completed;
                        trendChart.data.datasets[1].data = data.trend.new;
                        trendChart.update();
                    }

                    // Update Status Chart
                    if (statusChart) {
                        const s = data.stats;
                        const total = s.total_pending + s.total_submitted + s.total_selesai + s.total_rejected;
                        if (total === 0) {
                            $('#statusChart').addClass('d-none');
                            $('#statusChartEmpty').removeClass('d-none').addClass('d-flex');
                        } else {
                            $('#statusChart').removeClass('d-none');
                            $('#statusChartEmpty').removeClass('d-flex').addClass('d-none');
                            statusChart.data.datasets[0].data = [s.total_pending, s.total_submitted, s.total_selesai, s.total_rejected];
                            statusChart.update();
                        }
                    }
                    
                    // Load deadlines separately as it uses a different endpoint for now
                    loadDeadlineTasks();
                    updateLastUpdated();
                }
            }
        });
    }

    function renderRecentTasks(data) {
        let tasksHtml = '';
        if (data.length > 0) {
            tasksHtml = '<div class="list-group list-group-flush">';
            $.each(data, function (index, task) {
                let statusBadge = '';
                let statusClass = '';
                let iconClass = '';

                if (task.status === 'approved') {
                    statusBadge = 'Disetujui'; statusClass = 'success'; iconClass = 'fa-check-circle text-success';
                } else if (task.status === 'submitted') {
                    statusBadge = 'Menunggu'; statusClass = 'info'; iconClass = 'fa-clock text-info';
                } else if (task.status === 'rejected') {
                    statusBadge = 'Ditolak'; statusClass = 'danger'; iconClass = 'fa-times-circle text-danger';
                } else {
                    statusBadge = 'Pending'; statusClass = 'warning'; iconClass = 'fa-hourglass-half text-warning';
                }

                tasksHtml += `
                    <div class="list-group-item list-group-item-action px-3 py-3 border-0 mb-2 shadow-sm rounded-lg" 
                         style="transition: transform 0.2s; cursor: pointer; background: var(--glass-bg);">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div class="icon-circle-sm bg-light shadow-sm">
                                    <i class="fas ${iconClass}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="font-weight-bold text-dark text-truncate mb-0" style="display: block;">${task.tugas}</div>
                                <div class="text-muted small text-truncate">
                                    <i class="fas fa-user-circle mr-1"></i> ${task.user ? task.user.nama : 'System'}
                                </div>
                            </div>
                            <div class="text-right ml-2">
                                <span class="status-pill-premium status-pill-${statusClass}" style="font-size: 0.65rem;">
                                    ${statusBadge}
                                </span>
                                <div class="text-gray-400 mt-1" style="font-size: 0.6rem;">
                                    <i class="fas fa-clock mr-1"></i>${new Date(task.created_at).toLocaleDateString('id-ID')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            tasksHtml += '</div>';
        } else {
            tasksHtml = `
                <div class="empty-state py-4">
                    <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted mb-0">Belum ada tugas terbaru</p>
                </div>
            `;
        }
        $('#recent-tasks').html(tasksHtml);
        
        // Also update activity log with same data
        renderActivityLog(data);
    }

    function renderActivityLog(data) {
        if (data.length > 0) {
            let html = '';
            data.slice(0, 5).forEach((task, index) => {
                let activityText = '';
                let activityClass = 'primary';

                if (task.status === 'approved') {
                    activityText = 'Tugas disetujui'; activityClass = 'success';
                } else if (task.status === 'submitted') {
                    activityText = 'Tugas disubmit'; activityClass = 'info';
                } else if (task.status === 'rejected') {
                    activityText = 'Tugas ditolak'; activityClass = 'danger';
                } else {
                    activityText = 'Tugas baru dibuat'; activityClass = 'warning';
                }

                const timeAgo = getTimeAgo(new Date(task.updated_at || task.created_at));
                const creator = task.creator || { nama: 'System', profile_photo_path: null };
                const avatarUrl = creator.profile_photo_path ? `/storage/${creator.profile_photo_path}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(creator.nama)}&background=random&color=fff`;

                html += `
                    <div class="activity-bubble d-flex align-items-center justify-content-between p-3 mb-2" style="animation: fadeInUpSlight 0.4s ease forwards; animation-delay: ${index * 0.1}s">
                        <div class="activity-content flex-grow-1 mr-3">
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge badge-soft-${activityClass} mr-2" style="font-size: 0.65rem; border-radius: 4px; padding: 2px 6px;">${activityText}</span>
                                <small class="text-muted" style="font-size: 0.7rem;">${timeAgo}</small>
                            </div>
                            <div class="text-dark font-weight-bold small text-truncate" style="max-width: 160px;">${task.tugas}</div>
                        </div>
                        <div class="activity-creator d-flex align-items-center" title="Dibuat oleh: ${creator.nama}">
                            <div class="text-right mr-2 d-none d-sm-block">
                                <div class="small text-dark font-weight-bold" style="font-size: 0.75rem; line-height: 1;">${creator.nama.split(' ')[0]}</div>
                                <div class="text-muted" style="font-size: 0.6rem;">Pemberi</div>
                            </div>
                            <img src="${avatarUrl}" class="rounded-circle border border-white shadow-sm" style="width: 32px; height: 32px; object-fit: cover;" alt="${creator.nama}">
                        </div>
                    </div>
                `;
            });
            $('#activity-log').html(html);
        } else {
            $('#activity-log').html('<p class="text-center text-muted small mb-0">Belum ada aktivitas</p>');
        }
    }


    // Animate number counter
    function animateValue(elementId, endValue, isPercent = false) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const startValue = parseInt(element.textContent) || 0;
        const duration = 1000;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress = 1 - Math.pow(1 - progress, 4); // easeOutQuart
            const currentValue = Math.round(startValue + (endValue - startValue) * easeProgress);
            element.textContent = currentValue + (isPercent ? '%' : '');

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        requestAnimationFrame(update);
    }

    // Update completion rate circle
    function updateCompletionRate(data) {
        const total = data.total_tugas || 1;
        const completed = data.total_selesai || 0;
        const submitted = data.total_submitted || 0;
        const pending = data.total_pending || 0;

        const rate = Math.round((completed / total) * 100);
        const circumference = 440; // 2 * PI * 70
        const offset = circumference - (rate / 100) * circumference;

        animateValue('completion-rate', rate, true);
        animateValue('rate-approved', completed);
        animateValue('rate-submitted', submitted);
        animateValue('rate-pending', pending);

        // Animate circle
        $('#completion-circle').css('stroke-dashoffset', offset);
    }



    // Load deadline tasks
    function loadDeadlineTasks() {
        $.ajax({
            url: "/api/tugas",
            type: "GET",
            dataType: "json",
            cache: false,
            success: function (res) {
                if (res.success) {
                    const now = new Date();

                    // Helper function to calculate time difference
                    function getDeadlineInfo(deadlineStr) {
                        const deadline = new Date(deadlineStr);
                        const diffMs = deadline - now;
                        const diffMins = Math.floor(diffMs / (1000 * 60));
                        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

                        // Check if overdue
                        if (diffMs < 0) {
                            const overdueMins = Math.abs(diffMins);
                            const overdueHours = Math.abs(diffHours);
                            const overdueDays = Math.abs(diffDays);

                            if (overdueDays >= 1) {
                                return { class: 'overdue', text: `Terlambat ${overdueDays} hari`, isOverdue: true, priority: 0 };
                            } else if (overdueHours >= 1) {
                                return { class: 'overdue', text: `Terlambat ${overdueHours} jam`, isOverdue: true, priority: 0 };
                            } else {
                                return { class: 'overdue', text: `Terlambat ${overdueMins} menit`, isOverdue: true, priority: 0 };
                            }
                        }

                        // Same day - check hours left
                        if (diffDays === 0) {
                            if (diffHours < 1) {
                                return { class: 'urgent', text: `${diffMins} menit lagi!`, isOverdue: false, priority: 1 };
                            } else if (diffHours < 3) {
                                return { class: 'urgent', text: `${diffHours} jam lagi!`, isOverdue: false, priority: 2 };
                            } else {
                                return { class: 'urgent', text: `Hari ini (${diffHours} jam)`, isOverdue: false, priority: 3 };
                            }
                        }

                        // Tomorrow
                        if (diffDays === 1) {
                            return { class: 'soon', text: 'Besok', isOverdue: false, priority: 4 };
                        }

                        // 2-3 days
                        if (diffDays <= 3) {
                            return { class: 'safe', text: `${diffDays} hari lagi`, isOverdue: false, priority: 5 };
                        }

                        return { class: 'safe', text: `${diffDays} hari lagi`, isOverdue: false, priority: 6 };
                    }

                    // Filter tasks with upcoming deadlines (3 days) or overdue that are not completed
                    const deadlineTasks = res.data.filter(task => {
                        if (task.status === 'approved') return false;
                        if (!task.tanggal_selesai) return false;

                        const deadline = new Date(task.tanggal_selesai);
                        const diffMs = deadline - now;
                        const diffDays = diffMs / (1000 * 60 * 60 * 24);

                        // Show overdue (up to -7 days) or upcoming (up to 3 days)
                        return diffDays >= -7 && diffDays <= 3;
                    }).map(task => {
                        const info = getDeadlineInfo(task.tanggal_selesai);
                        return { ...task, deadlineInfo: info };
                    }).sort((a, b) => a.deadlineInfo.priority - b.deadlineInfo.priority)
                        .slice(0, 5);

                    // Count overdue
                    const overdueCount = deadlineTasks.filter(t => t.deadlineInfo.isOverdue).length;
                    const urgentCount = deadlineTasks.filter(t => !t.deadlineInfo.isOverdue).length;

                    if (overdueCount > 0) {
                        $('#deadline-count').text(overdueCount).removeClass('badge-danger badge-warning').addClass('badge-danger');
                    } else {
                        $('#deadline-count').text(urgentCount).removeClass('badge-danger badge-warning').addClass('badge-warning');
                    }

                    let html = '';
                    if (deadlineTasks.length > 0) {
                        deadlineTasks.forEach(task => {
                            const info = task.deadlineInfo;
                            const iconClass = info.isOverdue ? 'fa-exclamation-circle' : 'fa-clock';
                            const bgClass = info.isOverdue ? 'bg-danger' : 'bg-light';
                            const textClass = info.isOverdue ? 'text-white' : 'text-danger';

                            html += `
                                <div class="deadline-item d-flex align-items-center p-3 border-bottom ${info.isOverdue ? 'overdue-pulse' : ''}" 
                                     style="transition: background 0.2s; border-left: 3px solid ${info.isOverdue ? '#e74a3b' : 'transparent'};">
                                    <div class="mr-3">
                                        <div class="icon-circle-sm ${bgClass}" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <i class="fas ${iconClass} ${textClass}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="text-dark font-weight-bold text-truncate small mb-0" style="display: block;">${task.tugas}</div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-gray-400 mr-1" style="font-size: 0.65rem;"></i>
                                            <span class="text-muted text-truncate" style="font-size: 0.65rem;">${task.user ? task.user.nama : 'N/A'}</span>
                                        </div>
                                    </div>
                                    <div class="text-right ml-2 border-left pl-2">
                                        <div class="deadline-countdown ${info.class} px-2 py-1" style="font-size: 0.6rem; border-radius: 4px;">
                                            ${info.text}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = `
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="text-muted mb-0 small">Tidak ada deadline mendekat</p>
                            </div>
                        `;
                    }

                    $('#deadline-tasks').html(html);
                }
            }
        });
    }



    // Get time ago string
    function getTimeAgo(date) {
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Baru saja';
        if (diffMins < 60) return `${diffMins} menit lalu`;
        if (diffHours < 24) return `${diffHours} jam lalu`;
        if (diffDays < 7) return `${diffDays} hari lalu`;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    }

    // Update last updated time
    function updateLastUpdated() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        $('#last-updated').html(`<i class="fas fa-sync-alt mr-1"></i> Terakhir: ${timeStr}`);
    }

    // Refresh all data
    refreshAllData = function () {
        const $btn = $('#refresh-dashboard');
        $btn.find('i').addClass('fa-spin');

        loadDashboardSummary();

        setTimeout(() => {
            $btn.find('i').removeClass('fa-spin');
        }, 1000);
    };

    // Refresh button click
    $('#refresh-dashboard').on('click', refreshAllData);

    // Initial load
    loadDashboardSummary();

    // Initialize Charts - Declare variables before calling initCharts()
    let statusChart, trendChart;

    initCharts();

    // Refresh every 60 seconds with Visibility API
    setInterval(function () {
        if (document.visibilityState === 'visible') {
            refreshAllData();
        }
    }, 60000);

    function initCharts() {
        const statusEl = document.getElementById('statusChart');
        if (!statusEl) return;

        // Status Chart (Pie)
        const statusCtx = statusEl.getContext('2d');
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Menunggu Persetujuan', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#f4b619', '#2c9faf', '#17a673', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 5,
                    borderRadius: 2,
                    cutout: '75%'
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    }
                }
            }
        });

        const trendEl = document.getElementById('trendChart');
        if (!trendEl) return;

        // Trend Chart (Line)
        const trendCtx = trendEl.getContext('2d');

        // Create Gradients
        const gradSelesai = trendCtx.createLinearGradient(0, 0, 0, 400);
        gradSelesai.addColorStop(0, 'rgba(28, 200, 138, 0.2)');
        gradSelesai.addColorStop(1, 'rgba(28, 200, 138, 0)');

        const gradBaru = trendCtx.createLinearGradient(0, 0, 0, 400);
        gradBaru.addColorStop(0, 'rgba(78, 115, 223, 0.2)');
        gradBaru.addColorStop(1, 'rgba(78, 115, 223, 0)');

        const last7Days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            last7Days.push(date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
        }

        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [{
                    label: 'Tugas Selesai',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#1cc88a',
                    borderWidth: 3,
                    pointBackgroundColor: '#1cc88a',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#1cc88a',
                    pointHoverBorderColor: '#fff',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    backgroundColor: gradSelesai,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Tugas Baru',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#4e73df',
                    borderWidth: 3,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#4e73df',
                    pointHoverBorderColor: '#fff',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    backgroundColor: gradBaru,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 6,
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: "rgba(255, 255, 255, 0.9)",
                        bodyColor: "#2d3748",
                        titleColor: "#2d3748",
                        borderColor: "#e2e8f0",
                        borderWidth: 1,
                        padding: 12,
                        bodyFont: { size: 12 },
                        titleFont: { size: 12, weight: 'bold' }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { size: 10 }, color: '#a0aec0' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#e2e8f0', drawBorder: false },
                        ticks: { font: { size: 10 }, color: '#a0aec0', precision: 0 }
                    }
                }
            }
        });

        loadDashboardSummary();
    }


});
