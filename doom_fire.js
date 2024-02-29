const palette = [
	'#070707', '#1f0707', '#2f0f07', '#470f07',
	'#571707', '#671f07', '#771f07', '#8f2707',
	'#9f2f07', '#af3f07', '#bf4707', '#c74707',
	'#df4f07', '#df5707', '#df5707', '#d75f07',
	'#d7670f', '#cf6f0f', '#cf770f', '#cf7f0f',
	'#cf8717', '#c78717', '#c78f17', '#c7971f', 
	'#bf9f1f', '#bf9f1f', '#bfa727', '#bfa727',
	'#bfaf2f', '#b7af2f', '#b7b72f', '#b7b737',
	'#cfcf6f', '#dfdf9f', '#efefc7', '#ffffff'
];
const global = { w: 0, h: 0 };
const scale = 4;
const dots = [];

//Cette fonction nommée start() est responsable de l'initialisation de la simulation d'effet de feu. Voici ce qu'elle fait :
function start() {
    //elle calcule la largeur (global.w) et la hauteur (global.h) de la zone de rendu en fonction de la taille de la fenêtre du navigateur et de la valeur de l'échelle (scale). Elle utilise la fonction Math.min pour s'assurer que les dimensions ne dépassent pas x pixels de largeur et X pixels de hauteur.
	global.w = Math.min(375, Math.round(window.innerWidth / scale));
	global.h = Math.min(667, Math.round(window.innerHeight / scale));

    //Elle ajuste la largeur et la hauteur du canvas en fonction des dimensions calculées (global.w * scale et global.h * scale). Cela assure que la taille du canvas est proportionnelle à la taille de la fenêtre du navigateur et à l'échelle définie.
	const canvas = document.getElementById('frame');
	canvas.width = global.w * scale;
	canvas.height = global.h * scale;
	if (canvas.getContext) {
		ctx = canvas.getContext('2d');
		ctx.globalCompositeOperation = 'new content';
	}

	
	for (let x = 0; x < global.w; x++) {
		for (let y = 0; y < global.h; y++) {
			dots[y * global.w + x] = y == global.h - 1 ? 35 : 0;
		}
	}

	window.requestAnimationFrame(update);
}

function update() {
	if (ctx == null) return;
	window.requestAnimationFrame(update);

	ctx.fillStyle = 'rgba(0, 0, 0, 1)';
	ctx.fillRect(0, 0, global.w * scale, global.h * scale);

	
	for (let x = 0; x < global.w; x++) {
		let xp = x * scale;
		for (let y = 1; y < global.h; y++) {
			let rand = Math.round(Math.random() * 3);
			let from = y * global.w + x;
			let to = from - global.w - rand + 1;
			dots[to] = dots[from] - (rand & 2);

			let index = Math.max(0, dots[from]);
			if (index == 0) continue;
			ctx.fillStyle = palette[index];
			ctx.fillRect(xp, y * scale, scale, scale);
		}
	}
}

start();