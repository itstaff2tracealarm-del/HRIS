/**TASSI ATTENDANCE SYNC ENGINE
 * Optimized for dynamic modal loading and safe data parsing
*/

function loadData() {
  fetch('/phphr-main/phphr-main/public/syncAttendance.php?a=getLocalData')
    .then(res => res.json())
    .then(data => {
      let tbody = document.querySelector('#attendanceTableBody');
      if (!tbody) return;

        tbody.innerHTML = '';

      if (!data || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="py-10 text-slate-400 italic">No attendance records found today.</td></tr>`;
        return;
      }

      data.forEach(row => {
      
        // 🔥 SAFE ENCODING: Iniiwasan nito ang error sa special characters sa JSON
        let safeRowData = encodeURIComponent(JSON.stringify(row));

        // Path Generator Helper
        const getImgPath = (img) => {
          if (!img) return null;
            return img.startsWith('uploads') 
              ? 'https://tassi.tracealarm.com.ph/test_location/' + img 
              : '/phphr-main/phphr-main/' + img;
        };

        let imgSrcIn = getImgPath(row.image_in);
        let imgSrcOut = getImgPath(row.image_out);

        tbody.innerHTML += `
          <tr class="group hover:bg-slate-50/50 transition-colors">
            <td class="text-base text-slate-900 uppercase group-hover:text-red-600">${row.id}</td>
            <td class="px-6 py-4 text-center">
              <div class="text-base text-slate-900 uppercase group-hover:text-red-600">${row.card_no}</div>
            </td>

            <td class="px-6 py-4">
              <div class="flex items-center gap-3 text-left">
                ${imgSrcIn 
                ? `<img src="${imgSrcIn}" 
                  class="img-thumb clickable-img" 
                  data-info="${safeRowData}" 
                  data-type="IN">` 
                  : `<div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">-</div>`}
                <div>
                  <div class="text-base text-slate-900 uppercase group-hover:text-red-600">${row.time_in ?? '--:--'}</div>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter italic truncate max-w-[100px]">
                    📍 ${row.in_location ?? 'No location'}
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4">
              <div class="flex items-center gap-3 text-left">
                ${imgSrcOut 
                  ? `<img src="${imgSrcOut}" 
                  class="img-thumb clickable-img" 
                  data-info="${safeRowData}" 
                  data-type="OUT">` 
                  : `<div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300 group-hover:text-red-600">-</div>`}
                  
                <div>
                  <div class="text-base text-slate-900 uppercase group-hover:text-red-600">${row.time_out ?? '--:--'}</div>
                  <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter italic truncate max-w-[100px]">
                    📍 ${row.out_location ?? 'No location'}
                  </div>
                </div>
              </div>
            </td>
          </tr>
        `;
      });
    })
  .catch(err => console.error("Load Error:", err));
}

// ==========================================
// 🔥 GLOBAL CLICK LISTENER (FIXED)
// ==========================================
document.addEventListener("click", function(e) {
  // Tinitignan kung ang clinick ay may class na 'clickable-img'
  if (e.target.classList.contains("clickable-img")) {
    const rawData = e.target.getAttribute("data-info");
    const type = e.target.getAttribute("data-type"); // "IN" o "OUT"

    if (rawData) {
      try {
        // Decode at Parse ang data
        const row = JSON.parse(decodeURIComponent(rawData));
                
        // Kunin ang UI elements mula sa PHP
        const modal = document.getElementById('globalImageModal');
        const modalImg = document.getElementById('modalImageSource');
        const modalTitle = document.getElementById('modalTitle');

        if (!modal || !modalImg) {
          console.error("Modal elements not found in PHP file!");
          return;
        }

        // I-set ang Image Source
        modalImg.src = e.target.src;

        // I-set ang Title at Location base sa type
        const location = (type === "IN") ? (row.in_location || 'No location') : (row.out_location || 'No location');
        const time = (type === "IN") ? (row.time_in) : (row.time_out);

        modalTitle.innerHTML = `
          <div class="text-center">
            <div class="text-white font-black uppercase tracking-[0.2em] text-lg">${type} LOG: ${row.card_no}</div>
            <div class="text-red-500 text-[12px] mt-1 font-black italic">
              🕒 ${time} | 📍 ${location}
            </div>
          </div>
        `;

        // Ipakita ang modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

      } catch (err) {
        console.error("Modal Parsing Error:", err);
      }
    }
  }
});

// Helper para sa manual close button ng PHP
window.closeImageModal = function() {
  const modal = document.getElementById('globalImageModal');
  if(modal) {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }
};

// ==========================================
// 🔥 INTERVAL LOGIC
// ==========================================

// Initial Load
document.addEventListener('DOMContentLoaded', loadData);

// Live Table Refresh (5 sec)
setInterval(loadData, 5000);

// Auto Sync Trigger (2 sec gap after completion)
let isSyncing = false;
setInterval(() => {
  if (isSyncing) return;
  isSyncing = true;

  fetch('/phphr-main/phphr-main/public/syncAttendance.php?a=sync')
    .then(() => {
      console.log("Sync Cycle Complete");
      loadData();
    })
    .catch(err => console.error("Sync Error:", err))
    .finally(() => isSyncing = false);
}, 10000); // Ginawa kong 10s para hindi masyadong bugbog ang server