# Delete Tree Node (Excluir Nó da Árvore)

![Joomla Badge](https://img.shields.io/badge/Joomla-5091CD?style=for-the-badge&logo=joomla&logoColor=white) ![PHP Badge](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)![GitHub-100000](https://user-images.githubusercontent.com/107778190/174810453-ea17e321-809e-41da-bfbf-94f1c6d7dd09.svg)

## Conteúdo

- [Sobre](#sobre)
- [Uso](#uso)
  - [Recursively NO](#Recursively-NO)
  - [Recursively YES](#Recursively-YES)  

## Sobre

Este é um plug-in de lista, que tem como funcionalidade permitir ao usuário excluir um item (nó da hierarquia) vinculando os nós filhos ao nó anterior do excluído, por exemplo:
- **A**
	
	- **B** 
		
		
		-  **C**

Ao excluir o item "B", é estabelecido o vínculo de "C" para "A"

## Uso

Com o plug-in devidamente instalado no Joomla, podemos usa-los nos plug-ins de listas, conforme mostra a imagem abaixo:
![lista delete](https://user-images.githubusercontent.com/107778190/174809286-590763c5-601a-4dc2-a649-2c6dbd2ce8b4.jpeg)

Com o plug-in selecionado, é possível escolher a coluna pai, que seria o nó da árvore a ser excluído, e também nos permite fazer a exclusão de forma recursiva ou não, e o que seria essa forma recursiva?

### Recursively NO

Responsável por alterar o vínculo após a exclusão do item pai, como mostrado no exemplo anteriormente.

#### Recursively YES

Responsável por excluir todos os subitens a partir do item selecionado, ou seja, não faz o vínculo entre os nós, apenas exclui.
