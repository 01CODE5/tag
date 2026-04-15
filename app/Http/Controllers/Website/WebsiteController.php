<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
	public function downloadCertificatePdf(Request $request)
	{
		$data = $request->validate([
			'ref' => ['required', 'string', 'max:100'],
			'name' => ['required', 'string', 'max:255'],
			'age' => ['nullable', 'string', 'max:20'],
			'address' => ['required', 'string', 'max:500'],
			'purpose' => ['required', 'string', 'max:255'],
			'date' => ['nullable', 'string', 'max:100'],
			'template' => ['nullable', 'array'],
			'template.certificateType' => ['nullable', 'string', 'max:255'],
			'template.bodyHeading' => ['nullable', 'string', 'max:255'],
			'template.mainBody' => ['nullable', 'string', 'max:2000'],
			'template.purposeStatement' => ['nullable', 'string', 'max:2000'],
			'template.issuedLine' => ['nullable', 'string', 'max:500'],
			'template.signName' => ['nullable', 'string', 'max:255'],
			'template.signTitle' => ['nullable', 'string', 'max:255'],
			'template.barangayName' => ['nullable', 'string', 'max:255'],
			'template.barangayAddress' => ['nullable', 'string', 'max:500'],
		]);

		$pdf = $this->buildCertificatePdf($data);
		$filename = 'certificate-' . preg_replace('/[^A-Za-z0-9_-]+/', '-', $data['ref']) . '.pdf';

		return response($pdf, 200)
			->header('Content-Type', 'application/pdf')
			->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
			->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
			->header('Pragma', 'no-cache')
			->header('Expires', '0');
	}

	private function buildCertificatePdf(array $data): string
	{
		$name = trim((string) ($data['name'] ?? ''));
		$age = trim((string) ($data['age'] ?? ''));
		$address = trim((string) ($data['address'] ?? ''));
		$purpose = trim((string) ($data['purpose'] ?? ''));
		$date = trim((string) ($data['date'] ?? ''));
		$ref = trim((string) ($data['ref'] ?? ''));
		$template = is_array($data['template'] ?? null) ? $data['template'] : [];

		$barangayName = trim((string) ($template['barangayName'] ?? 'BARANGAY 192'));
		$barangayAddress = trim((string) ($template['barangayAddress'] ?? 'City/Municipality, Province'));
		$certificateType = trim((string) ($template['certificateType'] ?? 'BARANGAY CLEARANCE'));
		$bodyHeading = trim((string) ($template['bodyHeading'] ?? 'TO WHOM IT MAY CONCERN:'));
		$mainBodyTemplate = trim((string) ($template['mainBody'] ?? 'This is to certify that (NAME), (AGE) years old, a resident of (ADDRESS), is known to be of good moral character and has no derogatory records filed in this barangay.'));
		$purposeStatementTemplate = trim((string) ($template['purposeStatement'] ?? 'This certification is issued upon the request of the above-named person for (PURPOSE).'));
		$issuedLineTemplate = trim((string) ($template['issuedLine'] ?? 'Issued this (DATE) at (BARANGAY).'));
		$signName = trim((string) ($template['signName'] ?? 'Barangay Captain Name'));
		$signTitle = trim((string) ($template['signTitle'] ?? 'Punong Barangay'));

		$resolvedMainBody = $this->applyPlaceholders($mainBodyTemplate, $name, $age, $address, $purpose, $date, $barangayName);
		$resolvedPurpose = $this->applyPlaceholders($purposeStatementTemplate, $name, $age, $address, $purpose, $date, $barangayName);
		$resolvedIssuedLine = $this->applyPlaceholders($issuedLineTemplate, $name, $age, $address, $purpose, $date, $barangayName);

		$lines = [];
		$lines[] = ['size' => 16, 'text' => 'REPUBLIC OF THE PHILIPPINES'];
		$lines[] = ['size' => 14, 'text' => $barangayName !== '' ? $barangayName : 'BARANGAY 192'];
		$lines[] = ['size' => 11, 'text' => $barangayAddress !== '' ? $barangayAddress : 'City/Municipality, Province'];
		$lines[] = ['size' => 20, 'text' => $certificateType !== '' ? $certificateType : 'BARANGAY CLEARANCE'];
		$lines[] = ['size' => 11, 'text' => ''];
		$lines[] = ['size' => 12, 'text' => $bodyHeading !== '' ? $bodyHeading : 'TO WHOM IT MAY CONCERN:'];
		$lines[] = ['size' => 11, 'text' => ''];
		foreach ($this->wrapPdfText($resolvedMainBody, 78) as $chunk) {
			$lines[] = ['size' => 12, 'text' => $chunk];
		}
		$lines[] = ['size' => 11, 'text' => ''];
		foreach ($this->wrapPdfText($resolvedPurpose, 78) as $chunk) {
			$lines[] = ['size' => 12, 'text' => $chunk];
		}
		$lines[] = ['size' => 11, 'text' => ''];
		$lines[] = ['size' => 12, 'text' => 'Reference ID: ' . $ref];
		$lines[] = ['size' => 12, 'text' => $resolvedIssuedLine !== '' ? $resolvedIssuedLine : ('Date Requested: ' . ($date !== '' ? $date : now()->toDateString()))];
		$lines[] = ['size' => 11, 'text' => ''];
		$lines[] = ['size' => 12, 'text' => 'Issued by the Barangay Office for official record and reference.'];
		$lines[] = ['size' => 11, 'text' => ''];
		$lines[] = ['size' => 12, 'text' => $signName !== '' ? $signName : 'Barangay Captain Name'];
		$lines[] = ['size' => 11, 'text' => $signTitle !== '' ? $signTitle : 'Punong Barangay'];

		$contentLines = [];
		$contentLines[] = '0.9 w';
		$contentLines[] = '0 0 0 RG';
		$contentLines[] = '40 40 515 760 re S';

		$y = 760;
		foreach ($lines as $line) {
			$text = $this->escapePdfText($line['text']);
			$size = (int) $line['size'];
			$contentLines[] = 'BT /F1 ' . $size . ' Tf 1 0 0 1 70 ' . $y . ' Tm (' . $text . ') Tj ET';
			$y -= max(18, (int) round($size * 1.35));
		}

		$content = implode("\n", $contentLines);

		$objects = [];
		$objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
		$objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
		$objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>';
		$objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
		$objects[] = '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . "\nendstream";

		$pdf = "%PDF-1.4\n";
		$offsets = [];

		foreach ($objects as $index => $object) {
			$offsets[] = strlen($pdf);
			$pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
		}

		$xrefPosition = strlen($pdf);
		$pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
		$pdf .= "0000000000 65535 f \n";
		foreach ($offsets as $offset) {
			$pdf .= sprintf('%010d 00000 n %s', $offset, "\n");
		}
		$pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefPosition . "\n%%EOF";

		return $pdf;
	}

	private function escapePdfText(string $text): string
	{
		$text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
		return preg_replace('/[^\x09\x0A\x0D\x20-\x7E]/', '', $text) ?? '';
	}

	private function wrapPdfText(string $text, int $width): array
	{
		$words = preg_split('/\s+/', trim($text)) ?: [];
		$lines = [];
		$current = '';

		foreach ($words as $word) {
			$test = $current === '' ? $word : $current . ' ' . $word;
			if (strlen($test) > $width && $current !== '') {
				$lines[] = $current;
				$current = $word;
				continue;
			}
			$current = $test;
		}

		if ($current !== '') {
			$lines[] = $current;
		}

		return $lines;
	}

	private function applyPlaceholders(
		string $text,
		string $name,
		string $age,
		string $address,
		string $purpose,
		string $date,
		string $barangayName
	): string {
		$map = [
			'(NAME)' => $name !== '' ? $name : 'JUAN DELA CRUZ',
			'(AGE)' => $age !== '' ? $age : '21',
			'(ADDRESS)' => $address !== '' ? $address : 'Barangay 192',
			'(PURPOSE)' => $purpose !== '' ? $purpose : 'Personal requirement',
			'(DATE)' => $date !== '' ? $date : now()->toDateString(),
			'(BARANGAY)' => $barangayName !== '' ? $barangayName : 'BARANGAY 192',
		];

		return strtr($text, $map);
	}
}
