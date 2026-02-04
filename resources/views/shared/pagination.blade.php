<div id="paginationContainer" style="display: none; margin-top: 32px;">
    <div style="display: flex; justify-content: center; align-items: center; gap: 12px;">
        <button id="prevBtn" onclick="changePage('prev')" class="pagination-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M15 18l-6-6 6-6"></path></svg>
        </button>
        <span id="pageInfo" class="page-info"></span>
        <button id="nextBtn" onclick="changePage('next')" class="pagination-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"></path></svg>
        </button>
    </div>
</div>

<style>
    .pagination-btn {
        width: 44px;
        height: 44px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pagination-btn:hover:not(:disabled) {
        background: #7c3aed;
        transform: scale(1.05);
    }
    .pagination-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    .page-info {
        padding: 12px 28px;
        background: var(--card-light);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        font-weight: 800;
        color: var(--text-light);
        font-size: 15px;
    }
    body.dark-mode .page-info {
        background: var(--card-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }
</style>

<script>
    let paginationData = { current_page: 1, last_page: 1 };

    function updatePagination(data) {
        if (!data || !data.last_page) {
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }

        paginationData = data;
        const container = document.getElementById('paginationContainer');
        const pageInfo = document.getElementById('pageInfo');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        if (data.last_page <= 1) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';
        pageInfo.textContent = `صفحة ${data.current_page} من ${data.last_page}`;
        prevBtn.disabled = data.current_page <= 1;
        nextBtn.disabled = data.current_page >= data.last_page;
    }

    function changePage(direction) {
        const newPage = direction === 'next' 
            ? paginationData.current_page + 1 
            : paginationData.current_page - 1;
        
        if (newPage >= 1 && newPage <= paginationData.last_page) {
            if (typeof fetchData === 'function') {
                fetchData(newPage);
            }
        }
    }
</script>
