import React, { useEffect, useRef } from 'react';
import { Canvas } from '@react-three/fiber';
import { STLLoader } from 'three/examples/jsm/loaders/STLLoader';
import { OrbitControls } from '@react-three/drei';

export default function Viewer({ model }) {
    const meshRef = useRef();

    useEffect(() => {
        const loader = new STLLoader();
        loader.load(`/storage/${model.path}`, (geometry) => {
            if (meshRef.current) {
                meshRef.current.geometry = geometry;
            }
        });
    }, [model]);

    return (
        <div className="h-screen">
            <Canvas>
                <OrbitControls />
                <ambientLight intensity={0.5} />
                <pointLight position={[10, 10, 10]} />
                <mesh ref={meshRef}>
                    <meshStandardMaterial color="gray" />
                </mesh>
            </Canvas>

            {model.metadata && (
                <div className="fixed top-0 right-0 p-4 bg-white shadow-lg">
                    <h3>Model Information</h3>
                    <pre>{JSON.stringify(model.metadata, null, 2)}</pre>
                </div>
            )}
        </div>
    );
}
