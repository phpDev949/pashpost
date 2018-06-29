# pashpost
OOP php polish notation

Simple inline input (prefix) 

Output as infix (human readable) and evaluated

$prefix[0] = '+ * * 22 26 53 + * 66 8 + + * * * 7 76 25 44 78 100';

((22 * 26) * 53) + ((66 * 8) + (((((7 * 76) * 25) * 44) + 78) + 100))) = 616222

$prefix[1] = '+ + + 27 38 81 + * * 48 33 53 + * 91 53 + * 82 14 96';

(((27 + 38) + 81) + (((48 * 33) * 53) + ((91 * 53) + ((82 * 14) + 96)))) = 90165

$prefix[2] = '+ 57 + * 14 71 + * * 86 39 24 + * 48 3 * * 92 16 60';

(57 + ((14 * 71) + (((86 * 39) * 24) + ((48 * 3) + ((92 * 16) * 60))))) = 170011

$prefix[4] = '/ 32 / * 70 44 / * 77 89 - - + + * 12 45 15 47 90 50';

(32 / ((70 * 44) / ((77 * 89) / (((((12 * 45) + 15) + 47) - 90) - 50)))) = 0.15411255411255

$prefix[5] = '+ + 85.21 5.42 - * 34.96 37.59 - * 60.15 94.31 - * 47.53 59.03 / / 50.54 14.01 44';

((85.21 + 5.42) + ((34.96 * 37.59) - ((60.15 * 94.31) - ((47.53 * 59.03) - ((50.54 / 14.01) / 44))))) = -1462.3561868925

$prefix[6] = '- 0.61 + 38.2 / 46.08 - * 71.23 85.53 + 68.92 / 61.41 + * 46.79 88.71 / 9.93 27';

(0.61 - (38.2 + (46.08 / ((71.23 * 85.53) - (68.92 + (61.41 / ((46.79 * 88.71) + (9.93 / 27)))))))) = -37.597650206137

$prefix[7] = '- 50.08 / 47.99 + * 68.32 73.39 + / 80.06 46.73 / / / * 13.55 94.26 30.13 25.74 41';

(50.08 - (47.99 / ((68.32 * 73.39) + ((80.06 / 46.73) + ((((13.55 * 94.26) / 30.13) / 25.74) / 41))))) = 50.070432154431

