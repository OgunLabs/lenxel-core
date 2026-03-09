<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Enqueue AI page styles
wp_enqueue_style('lenxel-ai-page', LENXEL_PLUGIN_URL . 'assets/css/ai-page.css', array(), '1.0.0');
?>
<div class="lnx-help lenxel-content-section" id="lnx-ai" style="display: none;">
	<div id="lenxel-ai" class="get-start"></div>
	<div class="adjust-s-on-mobile lnx-pd-r-30">
		<p class="lnx-font-poppins lnx-pd-t-15 lnx-pd-b-15 lnx-fs-16 lnx-lh-24 lnx-fw-400 lenxel-color-767171" style="margin:0px;"><?php esc_html_e("The AI Course Generation feature allows course creators to automatically generate comprehensive course content using artificial intelligence. This powerful tool analyzes your course description and optional learning materials to create structured lessons, quizzes, assignments, and projects—saving significant time in course development.","lenxel-core"); ?></p>
		<div class="">
			<p class="lnx-font-poppins lnx-inline-flex lnx-pd-b-15 lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730" style="margin:0;padding:0;"><?php esc_html_e("How to Generate a Course with AI", "lenxel-core"); ?></p>
			<p class="lenxel-color-767171 lnx-fs-16">Follow these steps to quickly build a course draft using the AI tool:</p>
		</div>
		<div class="lnx-mobile-pd-t-30">
			<p class="lnx-flex lenxel-color-000000 lnx-fw-700"><span class="lnx-ws-10 lnx-fs-16 lnx-lh-36"><?php esc_html_e("Step 1:","lenxel-core"); ?> </span> <span class="lnx-ws-90 lnx-fs-16 lnx-lh-24 lnx-mr-auto"><?php esc_html_e("Access the Generator", "lenxel-core"); ?></span>
		<ul class="ul">
			<li class="li"><?php esc_html_e("Navigate to the Course Builder within your LMS.", "lenxel-core"); ?></li>
			<li class="li"><?php esc_html_e("Click on the 'Generate course with AI' button, located above the Name and Content fields.", "lenxel-core"); ?></li>
		</ul>
		</p>
			<div class=""><img style="width:400px;" src="<?php  echo esc_url(LENXEL_THEME_URL .'/images/Lenxel_Course_Builder.svg');?>"></div>
			<p class="lnx-flex lenxel-color-000000 lnx-fw-700"><span class="lnx-ws-10 lnx-fs-16 lnx-lh-36"><?php esc_html_e("Step 2:", "lenxel-core");?> </span> <span class="lnx-ws-90 lnx-fs-16 lnx-lh-24 lnx-mr-auto"><?php esc_html_e("Describe Your Course.", "lenxel-core"); ?></span>
			<ul class="ul">
				<li class="li"><?php esc_html_e("A modal window titled 'Generate Course with AI' will appear. \nIn the 'Describe your course' field, provide a detailed prompt about the course you want to create. This description should include:", "lenxel-core"); ?>
					<ul class="ul">
						<li class="li"><?php esc_html_e("Course Title/Topic (e.g., 'Introduction to Machine Learning')", "lenxel-core"); ?></li>
						<li class="li"><?php esc_html_e("Desired Outcome (e.g., 'Learn core ML concepts, build real models...')", "lenxel-core"); ?></li>
						<li class="li"><?php esc_html_e("Structural Details (e.g., 'Make it 9 lessons, 4 quizzes, 4 practical assignments, and 1 hands-on project.')", "lenxel-core"); ?></li>
						<li class="li"><?php esc_html_e("Example Prompt: 'Course Title: Introduction to Machine Learning: From Zero to Applied. Make it 9 lessons, 4 quizzes, 4 practical assignments, and 1 hands-on project. The outcome should be: Learn core ML concepts, build real models, and apply them to real-world data.'", "lenxel-core"); ?></li>
					</ul>
				</li>
			</ul>
		</p>
			<p class="lnx-flex lenxel-color-000000 lnx-fw-700"><span class="lnx-ws-10 lnx-fs-16 lnx-lh-36"><?php esc_html_e("Step 3:", "lenxel-core");?> </span> <span class="lnx-ws-90 lnx-fs-16 lnx-lh-24 lnx-mr-auto"><?php esc_html_e("Upload Lesson Assets (Optional)","lenxel-core"); ?></span>
			<ul class="ul">
				<li class="li"><?php esc_html_e("Under 'Upload lesson assets', you can optionally provide existing material (in PDF or TXT format, up to 10MB) that the AI can use as source material or inspiration for the course content.", "lenxel-core"); ?></li>
				<li class="li"><?php esc_html_e("You can Drag & Drop files or click 'Choose file' to upload.", "lenxel-core"); ?></li>
			</ul>
		</p>
			<p class="lnx-flex lenxel-color-000000 lnx-fw-700"><span class="lnx-ws-10 lnx-fs-16 lnx-lh-36"><?php esc_html_e("Step 4:", "lenxel-core");?> </span> <span class="lnx-ws-90 lnx-fs-16 lnx-lh-24 lnx-mr-auto"><?php esc_html_e(" Initiate Generation","lenxel-core"); ?></span>
		<ul class="ul">
			<li class="li"><?php esc_html_e("Once your description is entered and files (if any) are uploaded, click the 'Generate' button.", "lenxel-core"); ?></li>
			<li class="li"><?php esc_html_e("A loading screen will appear, indicating 'AI is building your course'. This may take a few minutes.", "lenxel-core"); ?></li>
		</ul>
		</p>
			<p class="lnx-flex lenxel-color-000000 lnx-fw-700"><span class="lnx-ws-10 lnx-fs-16 lnx-lh-36"><?php esc_html_e("Step 5:", "lenxel-core");?> </span> <span class="lnx-ws-90 lnx-fs-16 lnx-lh-24 lnx-mr-auto"><?php esc_html_e("Review and Edit the Result", "lenxel-core"); ?></span>
		<ul class="ul">
			<li class="li"><?php esc_html_e("The AI will populate the Title and Description fields of your course with the generated content.", "lenxel-core"); ?></li>
			<li class="li"><?php esc_html_e("A message, 'AI Generated Result!', confirms successful generation.", "lenxel-core"); ?></li>
			<li class="li"><?php esc_html_e("Always review the generated content for accuracy, tone, and completeness. You can edit the text directly in the fields as needed.", "lenxel-core"); ?></li>
			<li class="li"><?php esc_html_e("Click 'Save as draft' or 'Publish' to finalize your course", "lenxel-core"); ?></li>
		</ul>
		</p>
		<div class="" style="margin-top: 30px;">
			<p class="lnx-font-poppins lnx-inline-flex lnx-pd-b-15 lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730" style="margin:0;padding:0;"><?php esc_html_e("AI Credits", "lenxel-core"); ?></p>
			<p class="lenxel-color-767171 lnx-fs-16">The AI Course Generator feature utilizes a credit system.</p>
		</div>
		
		<ul class="ul">
			<li class="li"><p><span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e("Credit Usage: ", "lenxel-core"); ?></span><span><?php esc_html_e("Each time you successfully click 'Generate', a certain number of credits will be deducted from your account balance.", "lenxel-core"); ?></span></p></li>
			<li class="li"><p><span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e("Credit Cost: ", "lenxel-core"); ?></span><span><?php esc_html_e("The exact credit cost may vary based on the complexity of your request (e.g., length of the prompt, number of lessons requested) and the inclusion of uploaded assets. The cost will be clearly displayed before you confirm generation in future updates.", "lenxel-core"); ?></span></p></li>
			<li class="li"><p><span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e("Checking Balance: ", "lenxel-core"); ?></span><span><?php esc_html_e("Ensure you have sufficient credits before starting the generation process. Your current credit balance can be checked in ", "lenxel-core"); ?><a href="#" onclick="window.open('https://portal.lenxel.ai/dashboard', '_blank'); return false;" style="color: #007cba; text-decoration: underline;"><?php esc_html_e("https://portal.lenxel.ai/dashboard", "lenxel-core"); ?></a></span></p></li>
		</ul>
		
		<div class="" style="margin-top: 30px;">
			<p class="lnx-font-poppins lnx-inline-flex lnx-pd-b-15 lnx-fs-20 lnx-lh-32 lnx-fw-600 lenxel-color-135730" style="margin:0;padding:0;"><?php esc_html_e("Best Practices for Prompts", "lenxel-core"); ?></p>
			<p class="lenxel-color-767171 lnx-fs-16">To get the most accurate and useful course generation, follow these tips:</p>
		</div>
		<p> 
		<ul class="ul">
			<li class="li"><p><span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e("Be Specific: ", "lenxel-core"); ?></span><span><?php esc_html_e("Instead of 'A course on cooking,' try 'An intermediate course on Italian regional cooking, focusing on 5 key regions, each with 2 lessons, 1 quiz, and a practical recipe.'", "lenxel-core"); ?></span></p></li>
			<li class="li"><p><span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e("Define Audience: ", "lenxel-core"); ?></span><span><?php esc_html_e("Specify the target learner (e.g., 'for beginners,' 'for advanced developers')", "lenxel-core"); ?></span></p></li>
			<li class="li"><p><span class="lenxel-color-000000" style="font-weight:700;"><?php esc_html_e("Specify Length/Structure: ", "lenxel-core"); ?></span><span><?php esc_html_e("Include the desired number of lessons, quizzes, assignments, and expected video playback time.", "lenxel-core"); ?></span></p></li>
		</ul>
		
 			</div>
	</div>
</div>
