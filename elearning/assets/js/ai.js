// AI EduRAG Engine JS: Handles AI Tutor, Soal Generator, RPP, and Roadmap
class EduRagAI {
    static async askTutor(pertanyaan, subjectId, topik = '') {
        const res = await fetch('index.php?page=api_ai_tutor', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pertanyaan, subject_id: subjectId, topik })
        });
        return await res.json();
    }

    static async generateSoal(mapel, bab, jumlah, tipe, kesulitan) {
        const res = await fetch('index.php?page=api_ai_generate_soal', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mapel, bab, jumlah, tipe, kesulitan })
        });
        return await res.json();
    }

    static async saveSoalToBank(subjectId, bab, soalList) {
        const res = await fetch('index.php?page=api_ai_save_soal', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ subject_id: subjectId, bab, soal_list: soalList })
        });
        return await res.json();
    }

    static async generateRpp(mapel, bab, alokasiWaktu) {
        const res = await fetch('index.php?page=api_ai_generate_rpp', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mapel, bab, alokasi_waktu: alokasiWaktu })
        });
        return await res.json();
    }

    static async generateRoadmap(mapel, topik, targetHari) {
        const res = await fetch('index.php?page=api_ai_generate_roadmap', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mapel, topik, target_hari: targetHari })
        });
        return await res.json();
    }
}
