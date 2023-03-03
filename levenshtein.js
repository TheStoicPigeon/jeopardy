
export function GetDistance(g, a) {
  if (!g) {
    return false;
  }
  return levenshtein(g.split(''), a.split(''));
}

function levenshtein(g, a) {

  let min, max;

  if (a.length < g.lenth) {
    [min, max] = [a, g];
  } else
    [min, max] = [g, a];

  for (let i = 0; i < min.length; i++) {
    if (min[i] == max[i]) {
      min[i] = max[i] = null;
    }
  }

  let maxDist = 0;
  for (let i = 0; i < min.length; i++) {
    if (min[i]) {
      for (let j = 0; j < max.length; j++) {
        if (max[j]) {
          if (max[j] == min[i]) {
            max[j] = null;
            min[i] = null;
            if (Math.abs(j - i) > maxDist) {
              maxDist++;
            }
          }
        }
      }
    }
  }
  for (let i = 0; i < max.length; i++) {
    if (min[i]) {
      maxDist++;
    }
  }
  return (maxDist + Math.abs(min.length - max.length)) <= 2;
}

