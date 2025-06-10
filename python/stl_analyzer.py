import sys
import json
import numpy as np
import trimesh

def analyze_stl(file_path):
    mesh = trimesh.load_mesh(file_path)

    # Análisis básico
    analysis = {
        "volume": float(mesh.volume),
        "area": float(mesh.area),
        "bounds": mesh.bounds.tolist(),
        "center_mass": mesh.center_mass.tolist(),
        "is_watertight": mesh.is_watertight,
        "holes": len(mesh.holes) if hasattr(mesh, 'holes') else 0,
        "face_count": len(mesh.faces),
        "vertex_count": len(mesh.vertices)
    }

    # Detección de características
    # Detectar superficies planas
    planar_patches = mesh.facets()
    analysis["planar_surfaces"] = len(planar_patches)

    # Detectar dobleces (ángulos entre caras)
    edges = mesh.face_adjacency_angles
    analysis["bends"] = len(edges[edges > np.radians(30)].tolist())

    print(json.dumps(analysis))

if __name__ == "__main__":
    if len(sys.argv) > 1:
        analyze_stl(sys.argv[1])
