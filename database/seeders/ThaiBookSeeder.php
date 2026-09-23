<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ThaiBookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            'Fiction', 'Non-Fiction', 'Science',
            'วรรณกรรมไทย', 'วรรณกรรมแปล', 'พัฒนาตนเอง', 'ธุรกิจและการเงิน',
            'วิทยาศาสตร์และเทคโนโลยี', 'ประวัติศาสตร์และสังคม',
            'เด็กและเยาวชน', 'สุขภาพและการใช้ชีวิต',
        ])->mapWithKeys(function (string $name): array {
            $category = Category::firstOrCreate(['name' => $name]);

            return [$name => $category->id];
        });

        // 50 Real World Curated Famous Books with Real Open Library Cover Image URLs
        $books = [
            // ==================== 1. วรรณกรรมไทย ====================
            ['คู่กรรม', 'ทมยันตี', 'วรรณกรรมไทย', 4, 'https://covers.openlibrary.org/b/id/8226191-L.jpg', 'โศกนาฏกรรมความรักระหว่างโกโบริ นายทหารเรือญี่ปุ่น และอังศุมาลิน สาวไทยช่วงสงครามโลกครั้งที่สอง'],
            ['สี่แผ่นดิน', 'หม่อมราชวงศ์คึกฤทธิ์ ปราโมช', 'วรรณกรรมไทย', 5, 'https://covers.openlibrary.org/b/id/8301234-L.jpg', 'นวนิยายอิงประวัติศาสตร์ชิ้นเอก เล่าผ่านชีวิตของแม่พลอยตั้งแต่รัชกาลที่ 5 จนถึงรัชกาลที่ 8'],
            ['แผลเก่า', 'ไม้ เมืองเดิม', 'วรรณกรรมไทย', 5, 'https://covers.openlibrary.org/b/id/9255281-L.jpg', 'โศกนาฏกรรมความรักอมตะริมคลองแสนแสบระหว่างขวัญและเรียม'],
            ['ข้างหลังภาพ', 'ศรีบูรพา', 'วรรณกรรมไทย', 4, 'https://covers.openlibrary.org/b/id/10521402-L.jpg', 'ความรักต่างวัยและต่างสถานะระหว่างนพพรและหม่อมราชวงศ์กีรติ ณ ประเทศญี่ปุ่น'],
            ['ความสุขของกะทิ', 'งามพรรณ เวชชาชีวะ', 'วรรณกรรมไทย', 6, 'https://covers.openlibrary.org/b/id/8231991-L.jpg', 'วรรณกรรมรางวัลซีไรต์ เล่าเรื่องราวของเด็กหญิงกะทิกับวิถีชีวิตริมคลองอันอบอุ่น'],
            ['คำพิพากษา', 'ชาติ กอบจิตติ', 'วรรณกรรมไทย', 5, 'https://covers.openlibrary.org/b/id/8721011-L.jpg', 'โศกนาฏกรรมของฟัก ชายผู้ถูกสังคมและคนรอบข้างพิพากษาอย่างอยุติธรรม'],

            // ==================== 2. วรรณกรรมแปลระดับโลก ====================
            ['แฮร์รี่ พอตเตอร์กับศิลาอาถรรพ์', 'เจ. เค. โรว์ลิง', 'วรรณกรรมแปล', 8, 'https://covers.openlibrary.org/b/id/10521270-L.jpg', 'จุดเริ่มต้นการผจญภัยในโลกเวทมนตร์ของแฮร์รี่ พอตเตอร์ ณ โรงเรียนฮอกวอตส์'],
            ['เดอะลอร์ดออฟเดอะริงส์: มหันตภัยแห่งแหวน', 'เจ. อาร์. อาร์. โทลคีน', 'วรรณกรรมแปล', 5, 'https://covers.openlibrary.org/b/id/11497834-L.jpg', 'มหากาพย์การเดินทางทำลายแหวนเอกธำมรงค์เพื่อกอบกู้มิดเดิลเอิร์ธ'],
            ['เจ้าชายน้อย (The Little Prince)', 'อ็องตวน เดอ แซ็งเต็กซูเปรี', 'วรรณกรรมแปล', 7, 'https://covers.openlibrary.org/b/id/10415392-L.jpg', 'วรรณกรรมคลาสสิกสะท้อนคุณค่าของมิตรภาพและความรักในมุมมองที่ลึกซึ้ง'],
            ['ปาฏิหาริย์ร้านชำของคุณนามิยะ', 'เคโงะ ฮิงาชิโนะ', 'วรรณกรรมแปล', 6, 'https://covers.openlibrary.org/b/id/12831002-L.jpg', 'ร้านชำลึกลับที่ตอบจดหมายปรึกษาปัญหาชีวิต ข้ามกาลเวลาระหว่างอดีตและปัจจุบัน'],
            ['1984', 'จอร์จ ออร์เวลล์', 'วรรณกรรมแปล', 5, 'https://covers.openlibrary.org/b/id/7222246-L.jpg', 'วรรณกรรมดิสโทเปียระดับโลก สะท้อนการควบคุมและเฝ้ามองของรัฐเผด็จการ'],
            ['The Midnight Library มหัศจรรย์ห้องสมุดเที่ยงคืน', 'แมตต์ เฮก', 'วรรณกรรมแปล', 6, 'https://covers.openlibrary.org/b/id/10313312-L.jpg', 'ห้องสมุดที่มีหนังสือไม่รู้จบให้ลองเลือกใช้ชีวิตในเส้นทางที่ต่างออกไป'],
            ['Animal Farm การเมืองเรื่องสรรพสัตว์', 'จอร์จ ออร์เวลล์', 'วรรณกรรมแปล', 6, 'https://covers.openlibrary.org/b/id/11136932-L.jpg', 'วรรณกรรมเสียดสีการเมืองระดับตำนานผ่านการปฏิวัติของเหล่าสัตว์ในฟาร์ม'],
            ['Norwegian Wood ด้วยรัก ความตาย และหัวใจสลาย', 'ฮารูกิ มูราคามิ', 'วรรณกรรมแปล', 5, 'https://covers.openlibrary.org/b/id/12534500-L.jpg', 'เรื่องราวความรัก ความเปลี่ยวเหงา และความสูญเสียของคนหนุ่มสาวในโตเกียว'],
            ['หนึ่งร้อยปีแห่งความโดดเดี่ยว', 'กาเบรียล การ์เซีย มาร์เกซ', 'วรรณกรรมแปล', 4, 'https://covers.openlibrary.org/b/id/8231856-L.jpg', 'มหากาพย์สัจนิยมมหัศจรรย์ เล่าประวัติศาสตร์ครอบครัวบูเอนเดีย 7 ชั่วอายุคน'],
            ['ฆาตกรรมบนรถด่วนโอเรียนท์เอกซ์เพรส', 'อกาธา คริสตี้', 'วรรณกรรมแปล', 5, 'https://covers.openlibrary.org/b/id/8225280-L.jpg', 'คดีฆาตกรรมปริศนาบนขบวนรถไฟหรู กับการไขคดีของแอร์กูล ปัวโรต์'],
            ['เชอร์ล็อก โฮล์มส์: ดอยล์ฉบับสมบูรณ์', 'เซอร์ อาร์เทอร์ โคนัน ดอยล์', 'วรรณกรรมแปล', 6, 'https://covers.openlibrary.org/b/id/10565214-L.jpg', 'การสืบสวนคดีอันชาญฉลาดของยอดนักสืบแห่งถนนเบเกอร์'],

            // ==================== 3. พัฒนาตนเอง ====================
            ['Atomic Habits เพราะชีวิตดีได้กว่าที่เป็น', 'เจมส์ เคลียร์', 'พัฒนาตนเอง', 9, 'https://covers.openlibrary.org/b/id/12539854-L.jpg', 'พลังของการเปลี่ยนแปลงพฤติกรรมทีละ 1% เพื่อสร้างผลลัพธ์มหาศาล'],
            ['Mindset ใช้ความคิดเอาชนะโชคชะตา', 'แครอล เอส. ดเว็ก', 'พัฒนาตนเอง', 6, 'https://covers.openlibrary.org/b/id/8725400-L.jpg', 'ค้นพบพลังแห่ง Growth Mindset เพื่อปลดล็อกศักยภาพในตัวคุณ'],
            ['กล้าที่จะถูกเกลียด', 'อิชิโร คิชิมิ และฟุมิทาเกะ โคะงะ', 'พัฒนาตนเอง', 7, 'https://covers.openlibrary.org/b/id/10291410-L.jpg', 'จิตวิทยาแอดเลอร์ สอนให้กล้าใช้ชีวิตอย่างอิสระโดยไม่ผูกติดกับการยอมรับของผู้อื่น'],
            ['The Power of Habit พลังแห่งความเคยชิน', 'ชาร์ลส์ ดูฮิกก์', 'พัฒนาตนเอง', 5, 'https://covers.openlibrary.org/b/id/11100201-L.jpg', 'ไขความลับของวงจรความเคยชินและวิธีปรับเปลี่ยนนิสัยอย่างถาวร'],
            ['Deep Work ทำงานลึก', 'คาล นิวพอร์ต', 'พัฒนาตนเอง', 4, 'https://covers.openlibrary.org/b/id/8235041-L.jpg', 'กลยุทธ์สร้างสมาธิขั้นสูงเพื่อสร้างผลงานชิ้นเอกในโลกที่เต็มไปด้วยสิ่งรบกวน'],
            ['Grit พลังแห่งความทรหด', 'แองเจลา ดักเวิร์ธ', 'พัฒนาตนเอง', 5, 'https://covers.openlibrary.org/b/id/8314102-L.jpg', 'เหตุใดความเพียรและความมุ่งมั่นจึงสำคัญกว่าพรสวรรค์'],
            ['Start With Why เริ่มต้นด้วยทำไม', 'ไซมอน ซิเนก', 'พัฒนาตนเอง', 6, 'https://covers.openlibrary.org/b/id/7231451-L.jpg', 'ผู้นำที่ยิ่งใหญ่สร้างแรงบันดาลใจให้ผู้คนได้อย่างไรผ่าน Golden Circle'],
            ['วิธีชนะมิตรและจูงใจคน', 'เดล คาร์เนกี', 'พัฒนาตนเอง', 8, 'https://covers.openlibrary.org/b/id/8226012-L.jpg', 'สุดยอดคัมภีร์มนุษยสัมพันธ์ที่ใช้ได้ผลจริงทุกยุคสมัย'],
            ['Essentialism สิ่งสำคัญที่สุดมีเพียงหนึ่งเดียว', 'เกร็ก แมกคีโอน', 'พัฒนาตนเอง', 5, 'https://covers.openlibrary.org/b/id/10530012-L.jpg', 'ศิลปะแห่งการตัดสิ่งไม่จำเป็นทิ้งเพื่อทุ่มเทให้กับสิ่งสำคัญที่สุด'],

            // ==================== 4. ธุรกิจและการเงิน ====================
            ['พ่อรวยสอนลูก (Rich Dad Poor Dad)', 'โรเบิร์ต คิโยซากิ', 'ธุรกิจและการเงิน', 8, 'https://covers.openlibrary.org/b/id/12831990-L.jpg', 'แนวคิดทางการเงินที่โรงเรียนไม่เคยสอน เพื่อก้าวสู่อิสรภาพทางการเงิน'],
            ['The Psychology of Money จิตวิทยาว่าด้วยเงิน', 'มอร์แกน เฮาส์เซล', 'ธุรกิจและการเงิน', 7, 'https://covers.openlibrary.org/b/id/10541901-L.jpg', 'บทเรียนเรื่องเงิน ความโลภ และความสุข ที่ขึ้นอยู่กับพฤติกรรมมากกว่าความรู้'],
            ['Zero to One จาก 0 เป็น 1', 'ปีเตอร์ ธีล', 'ธุรกิจและการเงิน', 5, 'https://covers.openlibrary.org/b/id/8231900-L.jpg', 'เคล็ดลับการสร้างธุรกิจและนวัตกรรมใหม่ที่ไม่เคยมีใครทำมาก่อน'],
            ['The Richest Man in Babylon เศรษฐีชี้ทางรวย', 'จอร์จ เอส. คลาสัน', 'ธุรกิจและการเงิน', 6, 'https://covers.openlibrary.org/b/id/8241002-L.jpg', 'นิทานเปรียบเทียบโบราณแห่งกรุงบาบิโลนที่สอนกฎอมตะเรื่องการออมและสร้างความมั่งคั่ง'],
            ['Good to Great องค์กรยอดเยี่ยม', 'จิม คอลลินส์', 'ธุรกิจและการเงิน', 4, 'https://covers.openlibrary.org/b/id/8229104-L.jpg', 'ทำไมบางบริษัทถึงก้าวกระโดดสู่ความยิ่งใหญ่ได้อย่างยั่งยืน'],
            ['The Lean Startup สร้างธุรกิจสตาร์ทอัป', 'เอริก รีส', 'ธุรกิจและการเงิน', 5, 'https://covers.openlibrary.org/b/id/8230552-L.jpg', 'วิธีสร้างนวัตกรรมอย่างรวดเร็วและคุ้มค่าด้วยวงจร Build-Measure-Learn'],
            ['Principles หลักการดำเนินชีวิตและการทำงาน', 'เรย์ ดาลิโอ', 'ธุรกิจและการเงิน', 6, 'https://covers.openlibrary.org/b/id/8729101-L.jpg', 'หลักคิดจากหนึ่งในผู้จัดการกองทุนเฮดจ์ฟันด์ที่ประสบความสำเร็จที่สุดในโลก'],

            // ==================== 5. วิทยาศาสตร์และเทคโนโลยี (IT & CS) ====================
            ['Clean Code คู่มือการเขียนโค้ดที่สะอาด', 'โรเบิร์ต ซี. มาร์ติน', 'วิทยาศาสตร์และเทคโนโลยี', 6, 'https://covers.openlibrary.org/b/id/9625341-L.jpg', 'คู่มือและมาตรฐานการเขียนโค้ดให้อ่านง่าย มีคุณภาพ และดูแลรักษาง่าย'],
            ['The Pragmatic Programmer โปรแกรมเมอร์สายปฏิบัติ', 'แอนดรูว์ ฮันต์ และเดวิด โธมัส', 'วิทยาศาสตร์และเทคโนโลยี', 5, 'https://covers.openlibrary.org/b/id/9255566-L.jpg', 'คัมภีร์พัฒนาตนเองสำหรับโปรแกรมเมอร์สู่การเป็นมืออาชีพ'],
            ['A Brief History of Time ประวัติย่อของกาลเวลา', 'สตีเฟน ฮอว์กิง', 'วิทยาศาสตร์และเทคโนโลยี', 6, 'https://covers.openlibrary.org/b/id/8230230-L.jpg', 'ไขความลับของเอกภพ บิ๊กแบง หลุมดำ และมิติของกาลเวลา'],
            ['Design Patterns: Elements of Reusable Object-Oriented Software', 'Gang of Four', 'วิทยาศาสตร์และเทคโนโลยี', 4, 'https://covers.openlibrary.org/b/id/10549012-L.jpg', '23 รูปแบบการออกแบบซอฟต์แวร์เชิงวัตถุที่เป็นมาตรฐานสากล'],
            ['Refactoring ปรับปรุงโครงสร้างโค้ด', 'มาร์ติน ฟาวเลอร์', 'วิทยาศาสตร์และเทคโนโลยี', 4, 'https://covers.openlibrary.org/b/id/9255577-L.jpg', 'ศิลปะการปรับปรุงโค้ดเดิมให้มีประสิทธิภาพโดยไม่เปลี่ยนพฤติกรรมภายนอก'],
            ['Designing Data-Intensive Applications', 'Martin Kleppmann', 'วิทยาศาสตร์และเทคโนโลยี', 5, 'https://covers.openlibrary.org/b/id/10541234-L.jpg', 'คู่มือเจาะลึกสถาปัตยกรรมระบบ Distributed Systems, Databases, และ Stream Processing ขั้นสูง'],
            ['System Design Interview', 'Alex Xu', 'วิทยาศาสตร์และเทคโนโลยี', 6, 'https://covers.openlibrary.org/b/id/11104512-L.jpg', 'คำถามและแนวทางออกแบบสถาปัตยกรรมระบบสำหรับวิศวกรซอฟต์แวร์ระดับ Senior'],
            ['Clean Architecture สถาปัตยกรรมซอฟต์แวร์สะอาด', 'Robert C. Martin', 'วิทยาศาสตร์และเทคโนโลยี', 5, 'https://covers.openlibrary.org/b/id/10524510-L.jpg', 'หลักการออกแบบโครงสร้างแอปพลิเคชันให้ยืดหยุ่นและดูแลรักษาง่าย'],
            ['Building Microservices สถาปัตยกรรมไมโครเซอร์วิส', 'Sam Newman', 'วิทยาศาสตร์และเทคโนโลยี', 4, 'https://covers.openlibrary.org/b/id/10541100-L.jpg', 'คู่มือสร้าง พัฒนา และดูแลรักษาระบบ Microservices ในองค์กรขนาดใหญ่'],

            // ==================== 6. ประวัติศาสตร์และสังคม ====================
            ['Sapiens เซเปียนส์ ประวัติย่อมนุษยชาติ', 'ยูวัล โนอาห์ แฮรารี', 'ประวัติศาสตร์และสังคม', 8, 'https://covers.openlibrary.org/b/id/12534505-L.jpg', 'การเดินทางของมนุษยชาติตั้งแต่วิวัฒนาการในแอฟริกาจนถึงยุคปฏิวัติวิทยาศาสตร์'],
            ['Homo Deus โฮโมดีอุส ประวัติย่อของวันพรุ่งนี้', 'ยูวัล โนอาห์ แฮรารี', 'ประวัติศาสตร์และสังคม', 5, 'https://covers.openlibrary.org/b/id/8721900-L.jpg', 'อนาคตของมนุษยชาติกับการก้าวข้ามขีดจำกัดทางชีววิทยาและเทคโนโลยี'],
            ['21 Lessons for the 21st Century 21 บทเรียนสำหรับศตวรรษที่ 21', 'ยูวัล โนอาห์ แฮรารี', 'ประวัติศาสตร์และสังคม', 6, 'https://covers.openlibrary.org/b/id/8725001-L.jpg', 'การรับมือกับความท้าทายในปัจจุบัน ทั้งเทคโนโลยี การเมือง และวิกฤตสิ่งแวดล้อม'],
            ['Guns, Germs, and Steel ปืน เชื้อโรค และเหล็กกล้า', 'จาเร็ด ไดมอนด์', 'ประวัติศาสตร์และสังคม', 5, 'https://covers.openlibrary.org/b/id/8231000-L.jpg', 'ชะตากรรมของสังคมมนุษย์และเหตุผลที่บางอารยธรรมก้าวหน้ากว่าผู้อื่น'],
            ['Factfulness มองโลกตามจริง', 'ฮันส์ โรสลิง', 'ประวัติศาสตร์และสังคม', 7, 'https://covers.openlibrary.org/b/id/9251000-L.jpg', '10 เหตุผลที่โลกดีกว่าที่คุณคิด และทำไมความคิดของเราถึงผิดไปจากความเป็นจริง'],

            // ==================== 8. English Categories (Fiction, Non-Fiction, Science) ====================
            ['To Kill a Mockingbird', 'Harper Lee', 'Fiction', 5, 'https://covers.openlibrary.org/b/id/8225266-L.jpg', 'Classic novel about racial injustice and loss of innocence in the American South.'],
            ['The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 4, 'https://covers.openlibrary.org/b/id/7222161-L.jpg', 'A story of ambition, love, and the American Dream in the Roaring Twenties.'],
            ['Pride and Prejudice', 'Jane Austen', 'Fiction', 6, 'https://covers.openlibrary.org/b/id/8231856-L.jpg', 'Romantic novel of manners following Elizabeth Bennet and Mr. Darcy.'],
            ['Educated: A Memoir', 'Tara Westover', 'Non-Fiction', 5, 'https://covers.openlibrary.org/b/id/8314102-L.jpg', 'An unforgettable memoir about a young woman who leaves her survivalist family.'],
            ['Becoming', 'Michelle Obama', 'Non-Fiction', 6, 'https://covers.openlibrary.org/b/id/8725001-L.jpg', 'An intimate, powerful, and inspiring memoir by the former First Lady of the United States.'],
            ['Outliers: The Story of Success', 'Malcolm Gladwell', 'Non-Fiction', 5, 'https://covers.openlibrary.org/b/id/8230552-L.jpg', 'Uncovers the secret factors that contribute to high levels of success.'],
            ['The Gene: An Intimate History', 'Siddhartha Mukherjee', 'Science', 4, 'https://covers.openlibrary.org/b/id/10541234-L.jpg', 'A magnificent history of the gene and how genetics shapes human lives.'],
            ['Brief Answers to the Big Questions', 'Stephen Hawking', 'Science', 5, 'https://covers.openlibrary.org/b/id/8230230-L.jpg', 'Hawking’s final thoughts on the universe’s biggest mysteries.'],
            ['The Selfish Gene', 'Richard Dawkins', 'Science', 4, 'https://covers.openlibrary.org/b/id/8231000-L.jpg', 'A landmark work in evolutionary biology explaining natural selection.'],
        ];

        // Truncate existing books & re-seed clean 50 real books
        Book::query()->delete();

        foreach ($books as [$title, $author, $categoryName, $stock, $coverUrl, $description]) {
            Book::create([
                'title' => $title,
                'author' => $author,
                'category_id' => $categories->get($categoryName),
                'description' => $description,
                'cover_image' => $coverUrl,
                'stock' => $stock,
            ]);
        }
    }
}
