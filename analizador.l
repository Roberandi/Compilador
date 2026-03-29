%{
#include "sintactico.tab.h"
#include <string.h>
%}
%option yylineno
%%
"int"       { return INT; }
"float"     { return FLOAT; }
"print"     { return PRINT; }
[a-zA-Z_][a-zA-Z0-9_]* { yylval.texto = strdup(yytext); return ID; }
[0-9]+      { yylval.texto = strdup(yytext); return NUM; }
"="         { return ASSIGN; }
";"         { return SEMICOLON; }
"("         { return LPAREN; }
")"         { return RPAREN; }
[ \t\n]+    { /* ignorar */ }
.           { return ERROR; }
%%
int yywrap() { return 1; }