<?php

namespace Database\Seeders;

use App\Models\Paragraph;
use Illuminate\Database\Seeder;

class ParagraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paragraphs = [
            [
                'language' => 'en',
                'difficulty' => 'normal',
                'content' => 'The rapid advancement of artificial intelligence and machine learning has fundamentally transformed the landscape of modern technology, creating unprecedented opportunities for innovation across various sectors including healthcare, finance, and autonomous transportation. As these systems become increasingly integrated into our daily lives, the importance of developing robust, ethical, and transparent algorithms has never been more critical. Engineers and researchers are now focusing on explainable AI, which aims to make the decision-making processes of complex neural networks more understandable to human users, thereby fostering trust and ensuring accountability. Meanwhile, the rise of edge computing is enabling faster data processing and reduced latency by bringing computation closer to the source of data generation, which is essential for real-time applications such as smart cities and the Internet of Things. Despite these technological breakthroughs, significant challenges remain in the realms of data privacy, algorithmic bias, and the potential displacement of traditional jobs. It is imperative that policymakers, industry leaders, and academic institutions collaborate to establish comprehensive frameworks that guide the responsible development and deployment of these emerging technologies, ensuring that the benefits of the digital revolution are equitably distributed across all segments of society. Furthermore, the convergence of quantum computing with classical architectures promises to solve certain complex problems that are currently intractable, potentially leading to major discoveries in materials science and cryptography in the coming decades. As we navigate this era of rapid change, continuous learning and adaptation will be key for individuals and organizations alike to thrive in a world increasingly shaped by digital intelligence and global connectivity. The history of computer science teaches us that every major leap forward brings new responsibilities, and our success will depend on our ability to balance technical mastery with humanitarian values. This journey towards a more intelligent future requires not just technical prowess but also a deep commitment to ethical standards that protect the rights and dignity of every person in the digital age.'
            ],
            [
                'language' => 'id',
                'difficulty' => 'normal',
                'content' => 'Kemajuan pesat dalam bidang kecerdasan buatan dan pembelajaran mesin telah mengubah lanskap teknologi modern secara mendasar, menciptakan peluang inovasi yang belum pernah terjadi sebelumnya di berbagai sektor seperti kesehatan, keuangan, dan transportasi otonom. Seiring dengan semakin terintegrasinya sistem-sistem ini ke dalam kehidupan sehari-hari, pentingnya pengembangan algoritma yang kuat, etis, dan transparan menjadi semakin krusial bagi masa depan umat manusia secara global. Para insinyur dan peneliti kini mulai memfokuskan perhatian mereka pada konsep AI yang dapat dijelaskan, yang bertujuan untuk membuat proses pengambilan keputusan dari jaringan saraf yang kompleks menjadi lebih mudah dipahami oleh pengguna manusia, sehingga dapat menumbuhkan rasa percaya dan memastikan adanya akuntabilitas yang jelas. Sementara itu, kebangkitan komputasi tepi atau edge computing memungkinkan pemrosesan data yang lebih cepat dan pengurangan latensi dengan membawa proses komputasi lebih dekat ke sumber pembuatan data, yang sangat penting untuk aplikasi waktu nyata seperti kota cerdas dan ekosistem Internet of Things. Terlepas dari berbagai terobosan teknologi yang luar biasa ini, tantangan signifikan masih tetap ada di ranah privasi data, bias algoritma, dan potensi pergeseran lapangan kerja tradisional yang memerlukan perhatian khusus dari para pemangku kepentingan. Sangatlah penting bagi pembuat kebijakan, pemimpin industri, dan institusi akademik untuk bekerja sama dalam menetapkan kerangka kerja komprehensif yang memandu pengembangan serta penyebaran teknologi yang bertanggung jawab, guna memastikan bahwa manfaat revolusi digital didistribusikan secara adil di seluruh lapisan masyarakat tanpa terkecuali. Selain itu, konvergensi antara komputasi kuantum dengan arsitektur klasik menjanjikan solusi untuk masalah kompleks tertentu yang saat ini sulit dipecahkan, yang berpotensi mengarah pada penemuan besar dalam ilmu material dan kriptografi di masa depan. Saat kita menjelajahi era perubahan yang sangat cepat ini, pembelajaran berkelanjutan dan adaptasi akan menjadi kunci utama bagi individu maupun organisasi untuk dapat berkembang pesat di dunia yang semakin dibentuk oleh kecerdasan digital dan konektivitas global yang tanpa batas. Sejarah ilmu komputer mengajarkan kepada kita bahwa setiap lompatan besar ke depan membawa tanggung jawab baru yang lebih besar, dan keberhasilan kita akan sangat bergantung pada kemampuan kita untuk menyeimbangkan penguasaan teknis dengan nilai-nilai kemanusiaan yang luhur.'
            ],
        ];

        foreach ($paragraphs as $p) {
            Paragraph::create($p);
        }
    }
}
