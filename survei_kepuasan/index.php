<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Survei Kepuasan Pasien</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 text-gray-800 min-h-screen flex flex-col">

  <!-- HEADER -->
  <header class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 shadow-lg">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1588776814546-ec04b7f1c49b?auto=format&fit=crop&w=1500&q=80')] bg-cover bg-center opacity-20"></div>
    <div class="relative z-10 text-center px-6">
      <div class="flex justify-center mb-4">
        <div class="bg-white/20 p-6 rounded-full backdrop-blur-md shadow-lg">
          <i class="fas fa-hospital text-4xl text-white animate-pulse"></i>
        </div>
      </div>
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-lg">RS Survei Kepuasan Pasien</h1>
      <p class="mt-3 text-lg opacity-90">Berikan penilaian Anda untuk membantu kami meningkatkan layanan</p>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="flex-1 container mx-auto px-6 py-12 max-w-3xl">
    <div class="bg-white/80 backdrop-blur-xl shadow-xl rounded-3xl p-8 md:p-10 border border-gray-200">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">
        Survei Kepuasan Layanan
      </h2>
      <p class="text-center text-gray-600 mb-8">
        Waktu pengisian ± <strong>3-5 menit</strong>
      </p>

      <!-- FORM -->
      <form id="surveyForm" class="space-y-8">

        <!-- NOMR -->
        <div>
          <label for="nomr" class="block font-semibold text-gray-700 mb-2">
            <i class="fa fa-id-card text-blue-600 mr-1"></i>
            Nomor Rekam Medis (NOMR)
            <span class="text-red-500">*</span>
          </label>
          <input
            id="nomr"
            name="nomr"
            required
            placeholder="Contoh: RM123456"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition text-gray-700 placeholder-gray-400"
          />
        </div>

        <!-- QUESTIONS -->
        <div id="questions-container" class="space-y-6">
          <div class="text-center py-12 text-gray-500">
            <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-3"></i>
            <p>Memuat pertanyaan survei...</p>
          </div>
        </div>

        <!-- SARAN -->
        <div>
          <label for="saran" class="block font-semibold text-gray-700 mb-2">
            <i class="fa fa-comment-dots text-blue-600 mr-1"></i>
            Saran & Masukan (Opsional)
          </label>
          <textarea
            id="saran"
            name="saran"
            rows="4"
            placeholder="Tuliskan saran atau masukan Anda..."
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition text-gray-700 placeholder-gray-400 resize-none"
          ></textarea>
        </div>

        <!-- BUTTON -->
        <div class="text-center pt-4">
          <button
            type="submit"
            id="submitBtn"
            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-10 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300"
          >
            <i class="fa fa-paper-plane mr-2"></i>
            Kirim Survei
          </button>
          <p class="text-xs text-gray-500 mt-3">
            Data Anda bersifat rahasia dan digunakan untuk peningkatan layanan.
          </p>
        </div>
      </form>

      <!-- THANK YOU MESSAGE -->
      <div id="thankYouMessage" class="hidden text-center mt-12">
        <div class="flex justify-center mb-6">
          <div class="bg-green-100 p-6 rounded-full shadow-lg animate-bounce">
            <i class="fa fa-check-circle text-green-500 text-5xl"></i>
          </div>
        </div>
        <h3 class="text-3xl font-bold text-gray-800 mb-3">Terima Kasih!</h3>
        <p class="text-gray-600 mb-6">
          Masukan Anda membantu kami memberikan pelayanan yang lebih baik setiap harinya.
        </p>
        <button
          onclick="location.reload()"
          class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-3 rounded-lg font-semibold shadow-md hover:shadow-xl transition"
        >
          <i class="fa fa-redo mr-2"></i>
          Isi Ulang Survei
        </button>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-900 text-gray-300 py-8 text-center">
    <div class="mb-3">
      <i class="fas fa-hospital text-2xl text-blue-400"></i>
    </div>
    <p class="text-sm">&copy; 2025 RS - Survei Kepuasan Pasien</p>
    <p class="text-xs text-gray-500 mt-1">
      
    </p>
  </footer>

  <!-- SCRIPT -->
  <script>
    const ratingLabels = ["", "Sangat Tidak Puas", "Tidak Puas", "Cukup", "Puas", "Sangat Puas"];
    let surveyAnswers = {};
    let totalQuestions = 0;

    document.addEventListener("DOMContentLoaded", () => {
      loadQuestions();
    });

    async function loadQuestions() {
      try {
        const response = await fetch("api/get_questions.php");
        const questions = await response.json();
        if (questions.success) {
          totalQuestions = questions.data.length;
          renderQuestions(questions.data);
        } else {
          showError("Gagal memuat pertanyaan survei.");
        }
      } catch (err) {
        showError("Terjadi kesalahan saat memuat pertanyaan.");
      }
    }

    function renderQuestions(questions) {
      const container = document.getElementById("questions-container");
      container.innerHTML = "";
      questions.forEach((q, index) => {
        const el = document.createElement("div");
        el.className =
          "rounded-2xl border border-gray-200 bg-white p-6 hover:shadow-md transition relative";
        el.innerHTML = `
          <div class="flex items-start gap-3 mb-4">
            <span class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full flex items-center justify-center font-semibold">${index + 1}</span>
            <h3 class="text-gray-800 font-semibold text-lg flex-1">${q.question_text}<span class="text-red-500">*</span></h3>
          </div>
          <div class="flex justify-center gap-3" data-question-id="${q.id}">
            ${Array.from({ length: 5 })
              .map(
                (_, i) => `
                <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="${
                  i + 1
                }"><i class="fa fa-star"></i></button>`
              )
              .join("")}
          </div>
          <div id="label-${q.id}" class="text-center text-sm text-gray-500 mt-2">Klik bintang untuk menilai</div>
        `;
        container.appendChild(el);

        el.querySelectorAll(".star").forEach((star) => {
          star.addEventListener("click", () => {
            const rating = parseInt(star.dataset.rating);
            surveyAnswers[q.id] = rating;
            highlightStars(q.id, rating);
            document.getElementById(`label-${q.id}`).textContent = ratingLabels[rating];
            document.getElementById(`label-${q.id}`).classList = "text-center text-blue-600 font-medium mt-2";
          });
        });
      });
    }

    function highlightStars(id, rating) {
      const stars = document.querySelectorAll(`[data-question-id='${id}'] .star`);
      stars.forEach((star, index) => {
        star.classList.toggle("text-yellow-400", index < rating);
        star.classList.toggle("text-gray-300", index >= rating);
      });
    }

    document.getElementById("surveyForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const nomr = document.getElementById("nomr").value.trim();
      const saran = document.getElementById("saran").value.trim();
      if (!nomr) return showError("Nomor Rekam Medis wajib diisi.");
      if (Object.keys(surveyAnswers).length !== totalQuestions)
        return showError("Harap jawab semua pertanyaan survei.");

      const btn = document.getElementById("submitBtn");
      btn.disabled = true;
      btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Mengirim...';

      try {
        const res = await fetch("api/submit_survey.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ nomr, saran, answers: surveyAnswers }),
        });
        const result = await res.json();
        if (result.success) {
          document.getElementById("surveyForm").classList.add("hidden");
          document.getElementById("thankYouMessage").classList.remove("hidden");
        } else {
          showError("Gagal mengirim survei. Silakan coba lagi.");
          btn.disabled = false;
          btn.innerHTML = '<i class="fa fa-paper-plane mr-2"></i> Kirim Survei';
        }
      } catch {
        showError("Terjadi kesalahan saat mengirim data.");
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-paper-plane mr-2"></i> Kirim Survei';
      }
    });

    function showError(msg) {
      alert(msg);
    }
  </script>
</body>
</html>
