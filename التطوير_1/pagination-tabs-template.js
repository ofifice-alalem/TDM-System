// ✅ Pagination مع Tabs - استخدم هذا الكود في جميع Views

const token = '{{ $token }}';
let allRequests = [];
let currentStatus = 'all';
let currentPage = 1;
let lastPage = 1;

async function fetchRequests(page = 1) {
    try {
        let url = `/api/marketer/requests?page=${page}`;
        if (currentStatus !== 'all') {
            url += `&status=${currentStatus}`;
        }
        
        const response = await fetch(url, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const result = await response.json();
        
        if (result.data && result.data.data) {
            allRequests = result.data.data;
            currentPage = result.data.current_page;
            lastPage = result.data.last_page;
        } else {
            allRequests = result.data || [];
        }
        
        renderRequests();
        renderPagination();
    } catch (error) {
        console.error('Error:', error);
        showError();
    }
}

function switchTab(status, btn) {
    currentStatus = status;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentPage = 1;
    fetchRequests(1);
}

function renderPagination() {
    if (lastPage <= 1) return;
    
    const container = document.getElementById('requestsList');
    let paginationHTML = '<div style="display: flex; justify-content: center; gap: 8px; margin-top: 24px;">';
    
    if (currentPage > 1) {
        paginationHTML += `<button onclick="fetchRequests(${currentPage - 1})" style="padding: 10px 16px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">السابق</button>`;
    }
    
    paginationHTML += `<span style="padding: 10px 16px; background: var(--card-light); border-radius: 8px; font-weight: 700;">صفحة ${currentPage} من ${lastPage}</span>`;
    
    if (currentPage < lastPage) {
        paginationHTML += `<button onclick="fetchRequests(${currentPage + 1})" style="padding: 10px 16px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">التالي</button>`;
    }
    
    paginationHTML += '</div>';
    container.innerHTML += paginationHTML;
}
