%{
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

extern int yylex();
void yyerror(const char *s);

/* --- TABLA DE SÍMBOLOS --- */
char tabla_simbolos[50][30];
int total_var = 0;

int buscar_variable(char* nombre) {
    for(int i=0; i<total_var; i++) {
        if(strcmp(tabla_simbolos[i], nombre) == 0) return 1;
    }
    return 0;
}

void agregar_variable(char* nombre) {
    strcpy(tabla_simbolos[total_var++], nombre);
    // [SYM] etiqueta para la Tabla de Símbolos (Tipo | Nombre)
    printf("[SYM] int | %s\n", nombre); 
    // [JS] etiqueta para la Traducción
    printf("[JS] let %s;\n", nombre); 
}
%}

%union { char* texto; }
%token <texto> ID NUM
%token INT FLOAT PRINT ASSIGN SEMICOLON LPAREN RPAREN ERROR

%%
programa: sentencias ;

sentencias: sentencia | sentencias sentencia ;

sentencia:
    INT ID SEMICOLON {
        if(!buscar_variable($2)) {
            printf("[LOG] ✔ Semantica: '%s' declarada con exito.\n", $2);
            agregar_variable($2);
        } else {
            printf("[LOG] ❌ Error Semantico: La variable '%s' ya existe.\n", $2);
        }
    }
    | ID ASSIGN NUM SEMICOLON {
        if(buscar_variable($1)) {
            printf("[LOG] ✔ Sintaxis/Semantica: Asignacion valida a '%s'.\n", $1);
            printf("[JS] %s = %s;\n", $1, $3); 
        } else {
            printf("[LOG] ❌ Error Semantico: '%s' no ha sido declarada.\n", $1);
        }
    }
    | PRINT LPAREN ID RPAREN SEMICOLON {
        if(buscar_variable($3)) {
            printf("[LOG] ✔ Sintaxis: Llamada a print valida.\n");
            printf("[JS] console.log(%s);\n", $3); 
        } else {
            printf("[LOG] ❌ Error Semantico: '%s' no existe para imprimir.\n", $3);
        }
    }
    | error SEMICOLON {
        printf("[LOG] ❌ Error Sintactico: Estructura invalida detectada.\n");
    }
    ;
%%
void yyerror(const char *s) { /* Manejado en las reglas */ }
int main() { return yyparse(); }