from os import system
system("cls")
import mysql.connector


conexion=mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='ejemplo'
)
ruiz=conexion.cursor()
system("cls")
while True:
    nombre=input("Ingrese sus dos nombres:\n>")
    system("cls")
    apellido=input("Ingrese sus apellidos:\n>")
    system("cls")
    edad=int(input("Ingrese su edad:\n>"))
    system("cls")
    documento=int(input("Ingrese su numero de documento\n>"))
    system("cls")
    ruiz.execute('''
    insert into empleados (nombres,apellidos,edad,documento)
    VALUES(%s,%s,%s,%s)
    ''',(nombre,apellido,edad,documento))
    conexion.commit()